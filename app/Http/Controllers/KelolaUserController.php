<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class KelolaUserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('dashboard.kelolauser.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('dashboard.kelolauser.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'username' => 'required|min:3|max:255|unique:users',
            'email' => 'required|email:dns|unique:users',
            'password' => ['required', 'confirmed', Password::min(6)],
            'id_role' => 'required|exists:roles,id'
        ], [
            'nama.required' => 'Nama harus diisi',
            'username.required' => 'Username harus diisi',
            'username.min' => 'Username minimal 3 karakter',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'id_role.exists' => 'Role tidak valid'
        ]);

        try {
            $validatedData['password'] = Hash::make($validatedData['password']);
            User::create($validatedData);
            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menambahkan user!')->withInput();
        }
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('dashboard.kelolauser.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'username' => 'required|min:3|max:255|unique:users,username,'.$id,
            'email' => 'required|email:dns|unique:users,email,'.$id,
            'id_role' => 'required|exists:roles,id',
            'password' => 'nullable|required_with:password_confirmation|confirmed|min:6'
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);
        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}