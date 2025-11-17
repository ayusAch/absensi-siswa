<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
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
        return view('kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
            'wali_kelas' => 'nullable|string|max:255',
            'tahun_ajaran' => 'nullable|string|max:20',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas' => $request->wali_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kelas = Kelas::with(['siswa' => function($query) {
            $query->orderBy('nama_lengkap');
        }])->findOrFail($id);
        
        return view('kelas.show', compact('kelas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('kelas.edit', compact('kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kelas->id,
            'wali_kelas' => 'nullable|string|max:255',
            'tahun_ajaran' => 'nullable|string|max:20',
        ]);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas' => $request->wali_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui');
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