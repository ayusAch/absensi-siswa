<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isGuru()) {
        return $this->guruDashboard();
        }
        // return $this->siswaDashboard();

        // Default ke admin untuk sekarang
        return $this->adminDashboard();
    }

    private function adminDashboard()
    {
        // Basic Statistics untuk ADMIN
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $totalGuru = Guru::count();

        // Today's Attendance
        $tanggalHariIni = now()->toDateString();
        $hadirHariIni = Absensi::where('tanggal', $tanggalHariIni)
            ->where('status', 'Hadir')->count();
        $izinHariIni = Absensi::where('tanggal', $tanggalHariIni)
            ->where('status', 'Izin')->count();
        $sakitHariIni = Absensi::where('tanggal', $tanggalHariIni)
            ->where('status', 'Sakit')->count();
        $alphaHariIni = $totalSiswa - ($hadirHariIni + $izinHariIni + $sakitHariIni);

        // Recent Data untuk ADMIN
        $siswaTerbaru = Siswa::with('kelas')->latest()->take(5)->get();
        $absensiTerbaru = Absensi::with(['siswa', 'kelas'])
            ->latest()
            ->take(10)
            ->get();
        $kelasList = Kelas::withCount('siswa')->get();

        // Quick Stats untuk ADMIN
        $qrGenerated = Siswa::whereNotNull('qr_code')->count();
        $guruAktif = Guru::where('status', 'Aktif')->count();
        $siswaBaruBulanIni = Siswa::whereMonth('created_at', now()->month)->count();

        // Top Classes
        $kelasTerbaik = $this->getTopClasses();

        // Monthly Statistics
        $statistikBulanan = $this->getMonthlyStatistics();

        // Chart Data
        $chartData = $this->getAttendanceChartData();

        return view('dashboard.admin', compact(
            'totalSiswa',
            'totalKelas',
            'totalGuru',
            'hadirHariIni',
            'izinHariIni',
            'sakitHariIni',
            'alphaHariIni',
            'siswaTerbaru',
            'absensiTerbaru',
            'kelasList',
            'qrGenerated',
            'guruAktif',
            'siswaBaruBulanIni',
            'kelasTerbaik',
            'statistikBulanan',
            'chartData',
            'tanggalHariIni'
        ));
    }

    private function getTopClasses()
    {
        $tanggalHariIni = now()->toDateString();

        return Kelas::withCount('siswa')->with('waliKelas')
            ->get()
            ->map(function ($kelas) use ($tanggalHariIni) {
                $totalSiswa = $kelas->siswa_count;
                $hadir = Absensi::where('kelas_id', $kelas->id)
                    ->where('tanggal', $tanggalHariIni)
                    ->where('status', 'Hadir')
                    ->count();

                $persentase = $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 1) : 0;

                return [
                    'nama_kelas' => $kelas->nama_kelas,
                    'total_siswa' => $totalSiswa,
                    'hadir' => $hadir,
                    'persentase' => $persentase,
                    'wali_kelas' => $kelas->waliKelas->nama_lengkap ?? '-'
                ];
            })
            ->sortByDesc('persentase')
            ->take(3)
            ->values();
    }

    private function getMonthlyStatistics()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $totalSiswa = Siswa::count();
        $hariSampaiSekarang = now()->day;

        return [
            'hari_sampai_sekarang' => $hariSampaiSekarang,
            'total_hari_sekolah' => $hariSampaiSekarang, // Simplified
            'hadir_bulan_ini' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('status', 'Hadir')
                ->count(),
            'izin_bulan_ini' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('status', 'Izin')
                ->count(),
            'sakit_bulan_ini' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('status', 'Sakit')
                ->count(),
            'total_kehadiran' => $totalSiswa * $hariSampaiSekarang,
        ];
    }

    private function getAttendanceChartData()
    {
        $dates = [];
        $hadirData = [];
        $totalData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dates[] = Carbon::parse($date)->format('d M');

            $hadir = Absensi::where('tanggal', $date)
                ->where('status', 'Hadir')
                ->count();
            $hadirData[] = $hadir;

            $total = Siswa::count();
            $totalData[] = $total;
        }

        return [
            'labels' => $dates,
            'hadir' => $hadirData,
            'total' => $totalData,
        ];
    }

    // Tambahkan method ini di DashboardController
    private function guruDashboard()
    {
        return view('dashboard.guru');
    }

    // Method untuk siswa (akan dibuat nanti)
    private function siswaDashboard()
    {
        // Akan diimplementasi nanti
        return view('dashboard.siswa');
    }
}