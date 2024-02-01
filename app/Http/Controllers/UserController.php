<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return view('layouts.admin.Users.index', compact('user'));
    }

    public function data()
    {
        // $product = Product::all();
        $pengguna = User::orderBy('idUser', 'desc')->get();
        return datatables()
            ->of($pengguna)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengguna) {
                return view('layouts.admin.Users.tombol', ['data' => $pengguna]);
            })
            ->addColumn('nameCabang', function ($pengguna) {
                return $pengguna->cabang->name;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cabang = Cabang::all();
        return view('layouts.admin.Users.create', compact('cabang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
            'idCabang' => 'required',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'idCabang' => $request->idCabang,
        ]);
        return back()->with('success_message_create', 'Data Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = User::where('idUser', $id)->first();
        return response()->json(['result' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::where('idUser', $id)->first();
        $cabang = Cabang::all();
        return view('layouts.admin.Users.edit', compact('data', 'cabang'));
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
            'role' => $request->role,
            'idCabang' => $request->idCabang,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = bcrypt($request->password);
        }

        $user->update($dataToUpdate);

        return redirect('/pengguna')->with('success_message_update', 'Data Berhasil Diubah');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return back()->with('success_message_delete', 'Data Berhasil Dihapus');
    }
}
