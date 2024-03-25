<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengguna =  User::where('idUser', auth()->user()->idUser)->get();
        return view('layouts.admin.profile.index', ['pengguna' => $pengguna]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $user = User::where('idUser', $id)->first();

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if ($request->filled('password')) {
            $dataToUpdate['password'] = bcrypt($request->password);
        }

        $user->update($dataToUpdate);
        return redirect()->back()->with('success_message_update', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
