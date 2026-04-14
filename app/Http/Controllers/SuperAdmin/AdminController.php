<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\Kategori;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::with('kategori')->latest()->get();
        return view('superadmin.pengguna.admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('superadmin.pengguna.admin.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'username' => 'required|string|max:20|unique:admins,username',
            'password' => 'required|string|min:8|max:10|confirmed',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        Admin::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('superadmin.admin.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('superadmin.admin.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::findOrFail($id);
        $kategoris = Kategori::all();
        return view('superadmin.pengguna.admin.edit', compact('admin', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:50',
            'username' => [
                'required',
                'string',
                'max:20',
                Rule::unique('admins', 'username')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:8|max:10|confirmed',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'kategori_id' => $request->kategori_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('superadmin.admin.index')
            ->with('success', 'Data Admin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('superadmin.admin.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}
