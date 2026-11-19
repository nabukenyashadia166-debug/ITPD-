<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimRequestController;
use App\Http\Controllers\PostItemController;
use App\Http\Controllers\PostFoundItemsController;
use App\Models\ClaimItems;
use App\Models\PostItem;
use App\Models\PostFoundItems;
use App\Services\ItemSimilarityMatcher;
use Illuminate\Http\Request;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::get('/searchItems', function (Request $request) {
    $request->validate([
        'dateFrom' => ['nullable', 'date'],
        'dateTo' => ['nullable', 'date', 'after_or_equal:dateFrom'],
        'status' => ['nullable', 'in:active,resolved,claimed'],
    ]);

    $keyword = trim((string) $request->query('keyword', ''));
    $category = $request->query('category');
    $location = $request->query('location');
    $type = $request->query('type');
    $status = $request->query('status', 'active');
    $dateFrom = $request->query('dateFrom');
    $dateTo = $request->query('dateTo');

    $lostItems = PostItem::query()
        ->where('status', $status)
        ->when($keyword !== '', function ($query) use ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('item_name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhere('location', 'like', '%' . $keyword . '%');
            });
        })
        ->when($category, fn ($query) => $query->where('category', $category))
        ->when($location, fn ($query) => $query->where('location', 'like', '%' . $location . '%'))
        ->when($dateFrom, fn ($query) => $query->whereDate('date_lost', '>=', $dateFrom))
        ->when($dateTo, fn ($query) => $query->whereDate('date_lost', '<=', $dateTo))
        ->get()
        ->map(fn (PostItem $item) => [
            'id' => $item->id,
            'name' => $item->item_name,
            'category' => $item->category,
            'description' => $item->description,
            'location' => $item->location,
            'date' => $item->date_lost,
            'type' => 'Lost',
            'status' => $item->status,
            'photo' => $item->photo_path,
        ]);

    $foundItems = PostFoundItems::query()
        ->where('status', $status)
        ->when($keyword !== '', function ($query) use ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('item_name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhere('found_location', 'like', '%' . $keyword . '%');
            });
        })
        ->when($category, fn ($query) => $query->where('category', $category))
        ->when($location, fn ($query) => $query->where('found_location', 'like', '%' . $location . '%'))
        ->when($dateFrom, fn ($query) => $query->whereDate('found_date', '>=', $dateFrom))
        ->when($dateTo, fn ($query) => $query->whereDate('found_date', '<=', $dateTo))
        ->get()
        ->map(fn (PostFoundItems $item) => [
            'id' => $item->id,
            'name' => $item->item_name,
            'category' => $item->category,
            'description' => $item->description,
            'location' => $item->found_location,
            'date' => $item->found_date,
            'type' => 'Found',
            'status' => $item->status,
            'photo' => $item->item_photo,
        ]);

    $items = collect();
    if ($type !== 'found') {
        $items = $items->merge($lostItems);
    }
    if ($type !== 'lost') {
        $items = $items->merge($foundItems);
    }

    $items = match ($request->query('sortBy', 'recent')) {
        'oldest' => $items->sortBy('date'),
        'name' => $items->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE),
        default => $items->sortByDesc('date'),
    };

    return view('searchItems', ['items' => $items->values()]);
})->name('searchItems');

Route::get('/itemDetails', function (Request $request) {
    $validated = $request->validate([
        'type' => ['required', 'in:lost,found'],
        'id' => ['required', 'integer'],
    ]);

    if ($validated['type'] === 'lost') {
        $record = PostItem::findOrFail($validated['id']);
        $item = [
            'id' => $record->id,
            'type' => 'Lost',
            'name' => $record->item_name,
            'category' => $record->category,
            'description' => $record->description,
            'location' => $record->location,
            'date' => $record->date_lost,
            'status' => $record->status,
            'photo' => $record->photo_path,
        ];
    } else {
        $record = PostFoundItems::findOrFail($validated['id']);
        $item = [
            'id' => $record->id,
            'type' => 'Found',
            'name' => $record->item_name,
            'category' => $record->category,
            'description' => $record->description,
            'location' => $record->found_location,
            'date' => $record->found_date,
            'status' => $record->status,
            'photo' => $record->item_photo,
        ];
    }

    return view('itemDetails', compact('item'));
})->name('itemDetails');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $userId = $request->user()->id;
        $lostCount = PostItem::where('user_id', $userId)->count();
        $foundCount = PostFoundItems::where('user_id', $userId)->count();

        $recentLostItems = PostItem::query()
            ->where('status', 'active')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn (PostItem $item) => [
                'name' => $item->item_name,
                'category' => $item->category,
                'location' => $item->location,
                'date' => $item->date_lost,
                'type' => 'Lost',
                'status' => $item->status,
            ]);

        $recentFoundItems = PostFoundItems::query()
            ->where('status', 'active')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn (PostFoundItems $item) => [
                'name' => $item->item_name,
                'category' => $item->category,
                'location' => $item->found_location,
                'date' => $item->found_date,
                'type' => 'Found',
                'status' => $item->status,
            ]);

        $recentItems = $recentLostItems
            ->merge($recentFoundItems)
            ->sortByDesc('date')
            ->take(10)
            ->values();

        return view('dashboard', compact('lostCount', 'foundCount', 'recentItems'));
    })->name('dashboard');

    Route::get('/claimItems', function () {
        $foundItems = PostFoundItems::query()
            ->where('status', 'active')
            ->latest()
            ->get(['id', 'item_name', 'category']);
        return view('claimItems', compact('foundItems'));
    })->name('claimItems');
    Route::post('/claims', [ClaimRequestController::class, 'store'])->name('claim.store');

    Route::get('/claimRequest', function () {
        return view('claimrequest');
    })->name('claimRequest');
    Route::post('/claim-requests', [ClaimRequestController::class, 'storeDetailed'])->name('claimRequest.store');

    Route::middleware('is_admin')->group(function () {
        Route::get('/claimVerification', [ClaimRequestController::class, 'index'])
            ->name('claimVerification');
        Route::get('/claimVerification/{claim}', [ClaimRequestController::class, 'show'])
            ->name('claimVerification.show');
        Route::patch('/claimVerification/{claim}', [ClaimRequestController::class, 'update'])
            ->name('claimVerification.update');
        Route::get('/claimVerification/{claim}/evidence', [ClaimRequestController::class, 'evidence'])
            ->name('claimVerification.evidence');
    });

    Route::get('/myItems', function (Request $request) {
        $userId = $request->user()->id;
        $keyword = trim((string) $request->query('keyword', ''));
        $type = $request->query('type');
        $status = $request->query('status');

        $lostItems = PostItem::query()
            ->where('user_id', $userId)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('item_name', 'like', '%' . $keyword . '%')
                        ->orWhere('description', 'like', '%' . $keyword . '%')
                        ->orWhere('location', 'like', '%' . $keyword . '%');
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->get()
            ->map(fn (PostItem $item) => [
                'id' => $item->id,
                'name' => $item->item_name,
                'type' => 'Lost',
                'date' => $item->date_lost,
                'location' => $item->location,
                'status' => $item->status,
            ]);

        $foundItems = PostFoundItems::query()
            ->where('user_id', $userId)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('item_name', 'like', '%' . $keyword . '%')
                        ->orWhere('description', 'like', '%' . $keyword . '%')
                        ->orWhere('found_location', 'like', '%' . $keyword . '%');
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->get()
            ->map(fn (PostFoundItems $item) => [
                'id' => $item->id,
                'name' => $item->item_name,
                'type' => 'Found',
                'date' => $item->found_date,
                'location' => $item->found_location,
                'status' => $item->status,
            ]);

        $items = collect();
        if ($type !== 'found') {
            $items = $items->merge($lostItems);
        }
        if ($type !== 'lost') {
            $items = $items->merge($foundItems);
        }
        $items = $items->sortByDesc('date')->values();

        return view('myItems', [
            'items' => $items,
            'lostCount' => PostItem::where('user_id', $userId)->count(),
            'foundCount' => PostFoundItems::where('user_id', $userId)->count(),
            'activeCount' => PostItem::where('user_id', $userId)->where('status', 'active')->count()
                + PostFoundItems::where('user_id', $userId)->where('status', 'active')->count(),
        ]);
    })->name('myItems');

    Route::patch('/items/{type}/{id}/status', function (Request $request, string $type, int $id) {
        $validated = $request->validate([
            'status' => ['required', 'in:active,resolved'],
        ]);

        abort_unless(in_array($type, ['lost', 'found'], true), 404);

        $item = $type === 'lost'
            ? PostItem::findOrFail($id)
            : PostFoundItems::findOrFail($id);

        abort_unless(
            $request->user()->is_admin || $item->user_id === $request->user()->id,
            403
        );

        abort_if($item->status === 'claimed', 422, 'A claimed item cannot be reopened or changed here.');

        $item->update(['status' => $validated['status']]);

        return redirect()->route('myItems')->with(
            'success',
            $validated['status'] === 'resolved'
                ? 'The report was marked resolved and moved to the archive.'
                : 'The report was reopened and is visible on the active item board.'
        );
    })->name('items.status.update');

    Route::get('/postItem', function () {
        return view('postItem');
    })->name('postItem');
    Route::post('/postItem', [PostItemController::class, 'store'])->name('postItem.store');

    Route::get('/AdminDashboard', function (ItemSimilarityMatcher $matcher) {
        $lostItems = PostItem::query()
            ->latest()
            ->take(10)
            ->get()
            ->map(fn (PostItem $item) => [
                'id' => $item->id,
                'name' => $item->item_name,
                'type' => 'Lost',
                'date' => $item->date_lost,
                'location' => $item->location,
                'status' => $item->status,
                'reported_at' => $item->created_at,
            ]);

        $foundItems = PostFoundItems::query()
            ->latest()
            ->take(10)
            ->get()
            ->map(fn (PostFoundItems $item) => [
                'id' => $item->id,
                'name' => $item->item_name,
                'type' => 'Found',
                'date' => $item->found_date,
                'location' => $item->found_location,
                'status' => $item->status,
                'reported_at' => $item->created_at,
            ]);

        $recentItems = $lostItems
            ->merge($foundItems)
            ->sortByDesc('reported_at')
            ->take(10)
            ->values();
        $recentClaims = ClaimItems::query()->latest()->take(5)->get();
        $potentialMatches = $matcher->findPotentialMatches();

        $lostCount = PostItem::count();
        $foundCount = PostFoundItems::count();
        $openClaimCount = ClaimItems::where('status', 'pending')->count();

        return response()
            ->view('AdminDashboard', [
            'lostCount' => $lostCount,
            'foundCount' => $foundCount,
            'openClaimCount' => $openClaimCount,
            'recentItems' => $recentItems,
            'recentClaims' => $recentClaims,
            'potentialMatchCount' => count($potentialMatches),
            'potentialMatches' => array_slice($potentialMatches, 0, 5),
            ])
            ->header('Cache-Control', 'no-store, private, max-age=0');
    })->name('AdminDashboard')->middleware('is_admin');

    Route::get('/admin/dashboard/counts', function () {
        return response()
            ->json([
                'lost' => PostItem::count(),
                'found' => PostFoundItems::count(),
                'claims' => ClaimItems::where('status', 'pending')->count(),
            ])
            ->header('Cache-Control', 'no-store, private, max-age=0');
    })->name('AdminDashboard.counts')->middleware('is_admin');

    Route::get('/itemArchive', function (Request $request) {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'in:lost,found'],
            'status' => ['nullable', 'in:resolved,returned,closed,expired,claimed'],
        ]);

        $keyword = trim($validated['q'] ?? '');
        $type = $validated['type'] ?? null;
        $status = $validated['status'] ?? null;

        $lostItems = PostItem::query()
            ->where('status', '!=', 'active')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('item_name', 'like', '%' . $keyword . '%')
                        ->orWhere('id', $keyword);
                });
            })
            ->get()
            ->map(fn (PostItem $item) => [
                'id' => $item->id,
                'type' => 'lost',
                'name' => $item->item_name,
                'category' => $item->category,
                'location' => $item->location,
                'status' => $item->status,
                'date' => $item->date_lost,
                'reported_at' => $item->created_at,
            ]);

        $foundItems = PostFoundItems::query()
            ->where('status', '!=', 'active')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('item_name', 'like', '%' . $keyword . '%')
                        ->orWhere('id', $keyword);
                });
            })
            ->get()
            ->map(fn (PostFoundItems $item) => [
                'id' => $item->id,
                'type' => 'found',
                'name' => $item->item_name,
                'category' => $item->category,
                'location' => $item->found_location,
                'status' => $item->status,
                'date' => $item->found_date,
                'reported_at' => $item->created_at,
            ]);

        $archivedItems = collect();
        if ($type !== 'found') {
            $archivedItems = $archivedItems->merge($lostItems);
        }
        if ($type !== 'lost') {
            $archivedItems = $archivedItems->merge($foundItems);
        }
        $archivedItems = $archivedItems->sortByDesc('reported_at')->values();

        return view('itemArchive', [
            'items' => $archivedItems,
            'archivedCount' => PostItem::where('status', '!=', 'active')->count()
                + PostFoundItems::where('status', '!=', 'active')->count(),
            'returnedCount' => PostItem::whereIn('status', ['resolved', 'returned'])->count()
                + PostFoundItems::whereIn('status', ['resolved', 'returned'])->count(),
            'closedCount' => PostItem::whereIn('status', ['closed', 'expired', 'claimed'])->count()
                + PostFoundItems::whereIn('status', ['closed', 'expired', 'claimed'])->count(),
            'selectedType' => $type,
            'selectedStatus' => $status,
            'keyword' => $keyword,
        ]);
    })->name('itemArchive');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::get('/postFound', function () {
        return view('postFoundItems');
    })->name('postFoundItems');
    Route::post('/postFound', [PostFoundItemsController::class, 'store'])->name('postFoundItems.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
