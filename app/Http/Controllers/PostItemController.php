<?php

namespace App\Http\Controllers;

use App\Models\PostItem;
use App\Services\ItemSimilarityMatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('postitem.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('postitem.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ItemSimilarityMatcher $matcher): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'item_name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:1000'],
            'location' => ['required', 'string', 'max:255'],
            'date_lost' => ['required', 'date', 'before_or_equal:today'],
            'contact' => ['nullable', 'string', 'max:255'],
            'item_photo' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ]);

        $validated['photo_path'] = $request->file('item_photo')->store('lost-items', 'public');
        $validated['user_id'] = $request->user()->id;
        unset($validated['item_photo']);

        $postItem = PostItem::create($validated);
        $matches = $matcher->findFoundMatches($postItem);

        return redirect()->route('postItem')
            ->with('success', 'Your lost item report was saved.')
            ->with('item_matches', $matches)
            ->with('match_type', 'found');
    }

    /**
     * Display the specified resource.
     */
    public function show(PostItem $postItem)
    {
        return view('postitem.show', compact('postItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostItem $postItem)
    {
        return view('postitem.edit', compact('postItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostItem $postItem)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $postItem->title = $request->input('title');
        $postItem->content = $request->input('content');
        $postItem->save();

        return redirect()->route('postitem.index')->with('success', 'Post item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostItem $postItem)
    {
        $postItem->delete();
        return redirect()->route('postitem.index')->with('success', 'Post item deleted successfully.');
    }
}
