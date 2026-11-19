<?php

namespace App\Http\Controllers;

use App\Models\ClaimItems;
use Illuminate\Http\Request;

class ClaimItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $claimItems = ClaimItems::all();
        return view('claimitems.index', compact('claimItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('claimitems.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'condition' => 'required|string|max:255',
            'found_location' => 'required|string|max:255',
            'found_date' => 'required|date',
            'description' => 'required|string',
            'item_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'finder_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $claimItems = new ClaimItems();
        $claimItems->item_name = $request->input('item_name');
        $claimItems->category = $request->input('category');
        $claimItems->condition = $request->input('condition');
        $claimItems->found_location = $request->input('found_location');
        $claimItems->found_date = $request->input('found_date');
        $claimItems->description = $request->input('description');
        if ($request->hasFile('item_photo')) {
            $file = $request->file('item_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = public_path('/uploads/' . $filename);
            $file->move(public_path('/uploads'), $filename);
            $claimItems->item_photo = '/uploads/' . $filename;
        }
        $claimItems->finder_name = $request->input('finder_name');
        $claimItems->phone = $request->input('phone');
        $claimItems->email = $request->input('email');
        $claimItems->save();

        return redirect()->route('claimitems.index')->with('success', 'Claim item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClaimItems $claimItems)
    {
        return view('claimitems.show', compact('claimItems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClaimItems $claimItems)
    {
        return view('claimitems.edit', compact('claimItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClaimItems $claimItems)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'condition' => 'required|string|max:255',
            'found_location' => 'required|string|max:255',
            'found_date' => 'required|date',
            'description' => 'required|string',
            'item_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'finder_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $claimItems->item_name = $request->input('item_name');
        $claimItems->category = $request->input('category');
        $claimItems->condition = $request->input('condition');
        $claimItems->found_location = $request->input('found_location');
        $claimItems->found_date = $request->input('found_date');
        $claimItems->description = $request->input('description');
        if ($request->hasFile('item_photo')) {
            $file = $request->file('item_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = public_path('/uploads/' . $filename);
            $file->move(public_path('/uploads'), $filename);
            $claimItems->item_photo = '/uploads/' . $filename;
        }
        $claimItems->finder_name = $request->input('finder_name');
        $claimItems->phone = $request->input('phone');
        $claimItems->email = $request->input('email');
        $claimItems->save();

        return redirect()->route('claimitems.index')->with('success', 'Claim item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClaimItems $claimItems)
    {
        $claimItems->delete();
        return redirect()->route('claimitems.index')->with('success', 'Claim item deleted successfully.');
    }
}
