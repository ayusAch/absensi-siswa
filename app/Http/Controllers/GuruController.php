<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gurus = Guru::with('user')->get();
        return view('guru.index', compact('gurus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|unique:gurus,nip',
            'email' => 'required|email|unique:gurus,email',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('guru-fotos', 'public');
            $data['foto'] = $fotoPath;
        }

        Guru::create($data);

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        return view('guru.show', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        return view('guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|unique:gurus,nip,' . $guru->id,
            'email' => 'required|email|unique:gurus,email,' . $guru->id,
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($guru->foto) {
                \Storage::disk('public')->delete($guru->foto);
            }

            $fotoPath = $request->file('foto')->store('guru-fotos', 'public');
            $data['foto'] = $fotoPath;
        }

        $guru->update($data);

        // Jika guru sudah memiliki user, update nama di user juga
        if ($guru->user) {
            $guru->user->update(['name' => $request->nama_lengkap]);
        }

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        // Cek jika guru masih menjadi wali kelas di salah satu kelas
        if ($guru->kelas()->exists()) {
            return redirect()->route('guru.index')
                ->with('error', 'Tidak dapat menghapus guru yang masih menjadi wali kelas. Ubah wali kelas terlebih dahulu.');
        }

        // Hapus foto jika ada
        if ($guru->foto) {
            \Storage::disk('public')->delete($guru->foto);
        }

        // Hapus user jika ada
        if ($guru->user) {
            $guru->user->delete();
        }

        $guru->delete();

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }

    // ==================== USER MANAGEMENT METHODS ====================

    /**
     * Show form untuk membuat user guru
     */
    public function createUser(Guru $guru)
    {
        // Cek apakah guru sudah memiliki user
        if ($guru->user) {
            return redirect()->route('guru.index')
                ->with('error', 'Guru ini sudah memiliki akun user.');
        }

        return view('guru.create-user', compact('guru'));
    }

    /**
     * Store user untuk guru
     */
    public function storeUser(Request $request, Guru $guru)
    {
        // Validasi
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Buat user
        User::create([
            'name' => $guru->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
            'guru_id' => $guru->id,
        ]);

        return redirect()->route('guru.index')
            ->with('success', 'User untuk guru ' . $guru->nama_lengkap . ' berhasil dibuat.');
    }

    /**
     * Show form untuk edit user guru
     */
    public function editUser(Guru $guru)
    {
        if (!$guru->user) {
            return redirect()->route('guru.index')
                ->with('error', 'Guru ini belum memiliki akun user.');
        }

        return view('guru.edit-user', compact('guru'));
    }

    /**
     * Update user guru
     */
    public function updateUser(Request $request, Guru $guru)
    {
        if (!$guru->user) {
            return redirect()->route('guru.index')
                ->with('error', 'Guru ini belum memiliki akun user.');
        }

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $guru->user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'email' => $request->email,
            'name' => $guru->nama_lengkap, // Update nama juga
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $guru->user->update($data);

        return redirect()->route('guru.index')
            ->with('success', 'User untuk guru ' . $guru->nama_lengkap . ' berhasil diperbarui.');
    }

    /**
     * Hapus user guru
     */
    public function destroyUser(Guru $guru)
    {
        if (!$guru->user) {
            return redirect()->route('guru.index')
                ->with('error', 'Guru ini belum memiliki akun user.');
        }

        $guru->user->delete();

        return redirect()->route('guru.index')
            ->with('success', 'User untuk guru ' . $guru->nama_lengkap . ' berhasil dihapus.');
    }

    /**
     * Tampilkan daftar user guru (opsional)
     */
    public function listUsers()
    {
        $gurusWithUser = Guru::has('user')->with('user')->get();
        $gurusWithoutUser = Guru::doesntHave('user')->get();

        return view('guru.user-list', compact('gurusWithUser', 'gurusWithoutUser'));
    }
}