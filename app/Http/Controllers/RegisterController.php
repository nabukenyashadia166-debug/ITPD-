<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('register.index', ['registers' => Register::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('register.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Account type' => 'required|string|max:255',
            'full name' => 'required|string|max:255',
            'email address' => 'required|email|unique:registers,email address',
            'phone number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $register = Register::create($validatedData);

        return redirect()->route('registers.show', $register);
    }

    /**
     * Display the specified resource.
     */
    public function show(Register $register)
    {
        return view('register.show', compact('register'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Register $register)
    {
        return view('register.edit', compact('register'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Register $register)
    {
        $validatedData = $request->validate([
            'Account type' => 'required|string|max:255',
            'full name' => 'required|string|max:255',
            'email address' => 'required|email|unique:registers,email address,' . $register->id,
            'phone number' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        }

        $register->update($validatedData);

        return redirect()->route('registers.show', $register);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Register $register)
    {
        $register->delete();

        return redirect()->route('registers.index');
    }
}
