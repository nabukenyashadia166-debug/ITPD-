<?php

namespace App\Http\Controllers;

use App\Models\ClaimItems;
use App\Models\PostFoundItems;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClaimRequestController extends Controller
{
    public function index(): View
    {
        $claims = ClaimItems::query()
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(20);

        return view('claimVerification', [
            'claims' => $claims,
            'selectedClaim' => null,
        ]);
    }

    public function show(ClaimItems $claim): View
    {
        return view('claimVerification', [
            'claims' => ClaimItems::query()
                ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
                ->latest()
                ->paginate(20),
            'selectedClaim' => $claim->load('foundItem'),
        ]);
    }

    public function update(Request $request, ClaimItems $claim): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        DB::transaction(function () use ($claim, $validated): void {
            $lockedClaim = ClaimItems::query()
                ->lockForUpdate()
                ->findOrFail($claim->id);

            if ($lockedClaim->status !== 'pending') {
                throw ValidationException::withMessages([
                    'status' => 'This claim has already been reviewed.',
                ]);
            }

            if ($validated['status'] === 'approved' && $lockedClaim->found_item_id) {
                $foundItem = PostFoundItems::query()
                    ->lockForUpdate()
                    ->find($lockedClaim->found_item_id);

                if (! $foundItem || $foundItem->status !== 'active') {
                    throw ValidationException::withMessages([
                        'status' => 'This found item is no longer available to approve.',
                    ]);
                }

                $alreadyApproved = ClaimItems::query()
                    ->where('found_item_id', $foundItem->id)
                    ->where('status', 'approved')
                    ->exists();

                if ($alreadyApproved) {
                    throw ValidationException::withMessages([
                        'status' => 'Another claim for this item has already been approved.',
                    ]);
                }

                $foundItem->update(['status' => 'claimed']);
            }

            $lockedClaim->update(['status' => $validated['status']]);
        });

        return redirect()
            ->route('claimVerification.show', $claim)
            ->with('success', 'The claim was ' . $validated['status'] . '.');
    }

    public function evidence(ClaimItems $claim): StreamedResponse
    {
        abort_unless(
            $claim->proof_path && Storage::disk('local')->exists($claim->proof_path),
            404,
            'Claim evidence was not found.'
        );

        return Storage::disk('local')->download($claim->proof_path);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'found_item_id' => ['required', 'integer', Rule::exists('post_found_items', 'id')->where('status', 'active')],
            'fullName' => ['required', 'string', 'max:255'],
            'studentNumber' => ['required', 'string', 'max:100'],
            'contact' => ['required', 'string', 'max:255'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'description' => ['required', 'string', 'max:5000'],
            'declaration' => ['accepted'],
        ]);

        $foundItem = PostFoundItems::findOrFail($validated['found_item_id']);

        ClaimItems::create([
            'user_id' => $request->user()->id,
            'found_item_id' => $foundItem->id,
            'item_name' => $foundItem->item_name,
            'category' => $foundItem->category,
            'claimant_name' => $validated['fullName'],
            'student_id' => $validated['studentNumber'],
            'contact' => $validated['contact'],
            'description' => $validated['description'],
            'proof_path' => $request->file('proof')->store('claim-proofs'),
            'status' => 'pending',
        ]);

        return redirect()->route('claimItems')->with('success', 'Your claim request was submitted.');
    }

    public function storeDetailed(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'itemId' => ['required', 'string', 'max:100'],
            'itemName' => ['required', 'string', 'max:255'],
            'itemCategory' => ['required', 'string', 'max:255'],
            'fullName' => ['required', 'string', 'max:255'],
            'studentId' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'lostLocation' => ['nullable', 'string', 'max:255'],
            'proofFile' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'additionalInfo' => ['nullable', 'string', 'max:5000'],
            'declaration' => ['accepted'],
        ]);

        ClaimItems::create([
            'user_id' => $request->user()->id,
            'item_reference' => $validated['itemId'],
            'item_name' => $validated['itemName'],
            'category' => $validated['itemCategory'],
            'claimant_name' => $validated['fullName'],
            'student_id' => $validated['studentId'] ?? null,
            'contact' => $validated['phone'],
            'email' => $validated['email'],
            'description' => $validated['description'],
            'lost_location' => $validated['lostLocation'] ?? null,
            'additional_info' => $validated['additionalInfo'] ?? null,
            'proof_path' => isset($validated['proofFile'])
                ? $request->file('proofFile')->store('claim-proofs')
                : null,
            'status' => 'pending',
        ]);

        return redirect()->route('claimRequest')->with('success', 'Your claim request was submitted.');
    }
}
