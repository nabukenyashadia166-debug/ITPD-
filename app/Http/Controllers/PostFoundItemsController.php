<?php

namespace App\Http\Controllers;

use App\Models\PostFoundItems;
use App\Services\ItemSimilarityMatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostFoundItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postFoundItems = PostFoundItems::all();
        return view('postfounditems.index', compact('postFoundItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('postfounditems.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ItemSimilarityMatcher $matcher): RedirectResponse
    {
        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:255'],
            'found_location' => ['required', 'string', 'max:255'],
            'found_date' => ['required', 'date', 'before_or_equal:today'],
            'description' => ['required', 'string'],
            'item_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'finder_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        if ($request->hasFile('item_photo')) {
            $validated['item_photo'] = $request->file('item_photo')->store('found-items', 'public');
        }

        $validated['user_id'] = $request->user()->id;
        $postFoundItem = PostFoundItems::create($validated);
        $matches = $matcher->findLostMatches($postFoundItem);

        return redirect()->route('postFoundItems')
            ->with('success', 'Your found item report was saved.')
            ->with('item_matches', $matches)
            ->with('match_type', 'lost');
    }

    /**
     * Display the specified resource.
     */
    public function show(PostFoundItems $postFoundItems)
    {
        return view('postfounditems.show', compact('postFoundItems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostFoundItems $postFoundItems)
    {
        return view('postfounditems.edit', compact('postFoundItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostFoundItems $postFoundItems)
    {
        $request->validate([
            'item category' => 'required|string|max:255',
            'item name' => 'required|string|max:255',
            'item description' => 'required|string',
            'location found' => 'required|string|max:255',
            'Date found' => 'required|date',
            'contact information' => 'required|string|max:255',
        ]);

        $postFoundItems->item_category = $request->input('item category');
        $postFoundItems->item_name = $request->input('item name');
        $postFoundItems->item_description = $request->input('item description');
        $postFoundItems->location_found = $request->input('location found');
        $postFoundItems->Date_found = $request->input('Date found');
        $postFoundItems->contact_information = $request->input('contact information');
        $postFoundItems->save();

        return redirect()->route('postfounditems.index')->with('success', 'Post found item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostFoundItems $postFoundItems)
    {
        $postFoundItems->delete();
        return redirect()->route('postfounditems.index')->with('success', 'Post found item deleted successfully.');
    }
}
