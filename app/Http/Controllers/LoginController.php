<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logins = Login::all();
        return view('logins.index', compact('logins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('logins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $login = Login::create($request->all());
        return view('logins.show', compact('login'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Login $login)
    {
        return view('logins.show', compact('login'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Login $login)
    {
        return view('logins.edit', compact('login'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Login $login)
    {
        $login->update($request->all());
        return view('logins.show', compact('login'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Login $login)
    {
        $login->delete();
        return redirect()->route('logins.index');
    }
}
