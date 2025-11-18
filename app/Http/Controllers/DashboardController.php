<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek role user
        if (auth()->user()->isGuru()) {
            return $this->dashboardGuru();
        } elseif (auth()->user()->isAdmin()) {
            return $this->dashboardAdmin();
        } elseif (auth()->user()->isSiswa()) {
            return $this->dashboardSiswa();
        }

        return view('dashboard');
    }

    private function dashboardGuru()
    {
        $guru = auth()->user()->guru;

        // Jika guru tidak ditemukan, redirect dengan error
        if (!$guru) {
            return redirect()->route('login')->with('error', 'Data guru tidak ditemukan.');
        }

        $tanggalHariIni = Carbon::today();

        // Ambil kelas yang diampu oleh guru ini
        $kelasDiampu = Kelas::where('wali_kelas_id', $guru->id)
            ->withCount('siswa')
            ->get()
            ->map(function ($kelas) use ($tanggalHariIni) {
                // Hitung statistik kehadiran untuk setiap kelas
                $totalSiswa = $kelas->siswa_count;
                $hadirHariIni = Absensi::whereHas('siswa', function ($query) use ($kelas) {
                    $query->where('kelas_id', $kelas->id);
                })
                    ->whereDate('created_at', $tanggalHariIni)
                    ->where('status', 'Hadir')
                    ->count();

                $tidakHadirHariIni = $totalSiswa - $hadirHariIni;
                $persentaseHadir = $totalSiswa > 0 ? round(($hadirHariIni / $totalSiswa) * 100, 1) : 0;

                return (object) [
                    'id' => $kelas->id,
                    'nama_kelas' => $kelas->nama_kelas,
                    'siswa_count' => $totalSiswa,
                    'hadir_hari_ini' => $hadirHariIni,
                    'tidak_hadir_hari_ini' => $tidakHadirHariIni,
                    'persentase_hadir' => $persentaseHadir
                ];
            });

        // Hitung total siswa di semua kelas yang diampu
        $totalSiswaDiampu = $kelasDiampu->sum('siswa_count');

        // Statistik kehadiran hari ini untuk semua kelas yang diampu
        $totalHadirHariIni = Absensi::whereHas('siswa', function ($query) use ($guru) {
            $query->whereHas('kelas', function ($q) use ($guru) {
                $q->where('wali_kelas_id', $guru->id);
            });
        })
            ->whereDate('created_at', $tanggalHariIni)
            ->where('status', 'Hadir')
            ->count();

        $totalIzinHariIni = Absensi::whereHas('siswa', function ($query) use ($guru) {
            $query->whereHas('kelas', function ($q) use ($guru) {
                $q->where('wali_kelas_id', $guru->id);
            });
        })
            ->whereDate('created_at', $tanggalHariIni)
            ->where('status', 'Izin')
            ->count();

        $totalSakitHariIni = Absensi::whereHas('siswa', function ($query) use ($guru) {
            $query->whereHas('kelas', function ($q) use ($guru) {
                $q->where('wali_kelas_id', $guru->id);
            });
        })
            ->whereDate('created_at', $tanggalHariIni)
            ->where('status', 'Sakit')
            ->count();

        $totalAlphaHariIni = $totalSiswaDiampu - ($totalHadirHariIni + $totalIzinHariIni + $totalSakitHariIni);

        // Ambil 10 absensi terbaru dari kelas yang diampu
        $absensiTerbaru = Absensi::whereHas('siswa', function ($query) use ($guru) {
            $query->whereHas('kelas', function ($q) use ($guru) {
                $q->where('wali_kelas_id', $guru->id);
            });
        })
            ->with(['siswa', 'kelas'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.guru', compact(
            'kelasDiampu',
            'tanggalHariIni',
            'totalSiswaDiampu',
            'totalHadirHariIni',
            'totalIzinHariIni',
            'totalSakitHariIni',
            'totalAlphaHariIni',
            'absensiTerbaru'
        ));
    }

    private function dashboardAdmin()
    {
        // Logika dashboard admin
        $totalGuru = \App\Models\Guru::count();
        $totalSiswa = \App\Models\Siswa::count();
        $totalKelas = \App\Models\Kelas::count();

        $tanggalHariIni = Carbon::today();
        $totalAbsensiHariIni = Absensi::whereDate('created_at', $tanggalHariIni)->count();

        return view('dashboard.admin', compact(
            'totalGuru',
            'totalSiswa',
            'totalKelas',
            'totalAbsensiHariIni',
            'tanggalHariIni'
        ));
    }

    private function dashboardSiswa()
    {
        // Logika dashboard siswa
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan.');
        }

        $tanggalHariIni = Carbon::today();

        // Ambil absensi siswa hari ini
        $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('created_at', $tanggalHariIni)
            ->first();

        // Ambil riwayat absensi 7 hari terakhir
        $riwayatAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('created_at', '>=', Carbon::today()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.siswa', compact(
            'siswa',
            'absensiHariIni',
            'riwayatAbsensi',
            'tanggalHariIni'
        ));
    }
}