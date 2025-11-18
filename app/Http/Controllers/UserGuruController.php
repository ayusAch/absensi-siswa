<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserGuruController extends Controller
{
    /**
     * Display a listing of guru users
     */
    public function index()
    {
        $users = User::where('role', 'guru')
            ->with('guru')
            ->get();

        return view('users.guru.index', compact('users'));
    }

    /**
     * Show the form for creating new guru user
     */
    public function create()
    {
        $gurus = Guru::whereDoesntHave('user')->get();
        return view('users.guru.create', compact('gurus'));
    }

    /**
     * Store a newly created guru user
     */
    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id|unique:users,guru_id',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $guru = Guru::findOrFail($request->guru_id);

        User::create([
            'name' => $guru->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
            'guru_id' => $request->guru_id,
        ]);

        return redirect()->route('users.guru.index')
            ->with('success', 'User guru berhasil dibuat.');
    }

    /**
     * Show form for editing guru user
     */
    public function edit(User $user)
    {
        if ($user->role !== 'guru') {
            abort(404);
        }

        return view('users.guru.edit', compact('user'));
    }

    /**
     * Update guru user
     */
    public function update(Request $request, User $user)
    {
        if ($user->role !== 'guru') {
            abort(404);
        }

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'email' => $request->email,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.guru.index')
            ->with('success', 'User guru berhasil diperbarui.');
    }

    /**
     * Remove guru user
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'guru') {
            abort(404);
        }

        $user->delete();

        return redirect()->route('users.guru.index')
            ->with('success', 'User guru berhasil dihapus.');
    }
}