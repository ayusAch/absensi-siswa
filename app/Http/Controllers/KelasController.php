<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::withCount('siswa')->get();
        return view('kelas.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = Guru::orderBy('nama_lengkap')->get(); // Ganti 'nama' dengan 'nama_lengkap'
        return view('kelas.create', compact('gurus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
            'wali_kelas_id' => 'nullable|exists:gurus,id', // Ubah validasi
            'tahun_ajaran' => 'nullable|string|max:20',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id, 
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kelas = Kelas::with('guru')->findOrFail($id); 
        $gurus = Guru::orderBy('nama_lengkap')->get();
        return view('kelas.edit', compact('kelas', 'gurus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kelas->id,
            'wali_kelas_id' => 'nullable|exists:gurus,id', // Ubah validasi
            'tahun_ajaran' => 'nullable|string|max:20',
        ]);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id, // Simpan ID guru
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kelas = Kelas::with([
            'siswa' => function ($query) {
                $query->orderBy('nama_lengkap');
            }
        ])->findOrFail($id);

        return view('kelas.show', compact('kelas'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        // Cek apakah kelas memiliki siswa
        if ($kelas->siswa()->count() > 0) {
            return redirect()->route('kelas.index')
                ->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa. Pindahkan siswa terlebih dahulu.');
        }

        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        $totalKelas = Kelas::count();
        $kelasWithSiswa = Kelas::has('siswa')->count();

        return [
            'total_kelas' => $totalKelas,
            'kelas_aktif' => $kelasWithSiswa,
        ];
    }
}