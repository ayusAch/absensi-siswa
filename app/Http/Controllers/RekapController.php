<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Siswa;
use Carbon\Carbon;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Tanggal dari input atau hari ini
        $tanggal = $request->get('tanggal', now()->toDateString());
        $kelasId = $request->get('kelas_id');
        $bulan = $request->get('bulan', now()->format('Y-m'));

        // Jika user adalah guru, hanya tampilkan kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;

            if (!$guru) {
                return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
            }

            $kelasList = Kelas::where('wali_kelas_id', $guru->id)->withCount('siswa')->get();

            // Validasi jika guru memilih kelas tertentu
            if ($kelasId) {
                $kelas = Kelas::where('id', $kelasId)
                    ->where('wali_kelas_id', $guru->id)
                    ->first();

                if (!$kelas) {
                    return redirect()->route('rekap.index')
                        ->with('error', 'Anda tidak memiliki akses ke kelas ini.');
                }
            }
        } else {
            // Admin bisa lihat semua kelas
            $kelasList = Kelas::withCount('siswa')->get();
        }

        // Rekap Harian
        $rekapHarian = $this->getRekapHarian($tanggal, $kelasId);

        // Rekap Bulanan
        $rekapBulanan = $this->getRekapBulanan($bulan, $kelasId);

        // Statistik Overview
        $statistik = $this->getStatistikOverview($tanggal, $kelasId);

        return view('rekap.index', compact(
            'rekapHarian',
            'rekapBulanan',
            'statistik',
            'tanggal',
            'bulan',
            'kelasList',
            'kelasId'
        ));
    }

    /**
     * Get rekap data harian
     */
    private function getRekapHarian($tanggal, $kelasId = null)
    {
        // Jika user adalah guru, hanya tampilkan kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;
            $query = Kelas::where('wali_kelas_id', $guru->id)->withCount('siswa');
        } else {
            $query = Kelas::withCount('siswa');
        }

        if ($kelasId) {
            $query->where('id', $kelasId);
        }

        $kelasList = $query->get();
        $rekap = [];

        foreach ($kelasList as $kelas) {
            $totalSiswa = $kelas->siswa_count;
            $hadir = Absensi::where('kelas_id', $kelas->id)
                ->where('tanggal', $tanggal)
                ->where('status', 'Hadir')
                ->count();
            $izin = Absensi::where('kelas_id', $kelas->id)
                ->where('tanggal', $tanggal)
                ->where('status', 'Izin')
                ->count();
            $sakit = Absensi::where('kelas_id', $kelas->id)
                ->where('tanggal', $tanggal)
                ->where('status', 'Sakit')
                ->count();
            $alpha = $totalSiswa - ($hadir + $izin + $sakit);

            $rekap[$kelas->id] = [
                'kelas' => $kelas->nama_kelas,
                'total_siswa' => $totalSiswa,
                'Hadir' => $hadir,
                'Izin' => $izin,
                'Sakit' => $sakit,
                'Alpha' => $alpha,
                'persen_hadir' => $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 1) : 0,
            ];
        }

        return $rekap;
    }

    /**
     * Get rekap data bulanan
     */
    private function getRekapBulanan($bulan, $kelasId = null)
    {
        $startDate = Carbon::parse($bulan)->startOfMonth();
        $endDate = Carbon::parse($bulan)->endOfMonth();

        // Jika user adalah guru, hanya tampilkan kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;
            $query = Kelas::where('wali_kelas_id', $guru->id)->withCount('siswa');
        } else {
            $query = Kelas::withCount('siswa');
        }

        if ($kelasId) {
            $query->where('id', $kelasId);
        }

        $kelasList = $query->get();
        $rekap = [];

        foreach ($kelasList as $kelas) {
            $totalSiswa = $kelas->siswa_count;
            $hariSekolah = $this->getHariSekolah($startDate, $endDate);
            $totalKehadiran = $hariSekolah * $totalSiswa;

            $hadir = Absensi::where('kelas_id', $kelas->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('status', 'Hadir')
                ->count();
            $izin = Absensi::where('kelas_id', $kelas->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('status', 'Izin')
                ->count();
            $sakit = Absensi::where('kelas_id', $kelas->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('status', 'Sakit')
                ->count();

            $rekap[$kelas->id] = [
                'kelas' => $kelas->nama_kelas,
                'total_siswa' => $totalSiswa,
                'hari_sekolah' => $hariSekolah,
                'total_kehadiran' => $totalKehadiran,
                'Hadir' => $hadir,
                'Izin' => $izin,
                'Sakit' => $sakit,
                'persen_hadir' => $totalKehadiran > 0 ? round(($hadir / $totalKehadiran) * 100, 1) : 0,
            ];
        }

        return $rekap;
    }

    /**
     * Get statistik overview
     */
    private function getStatistikOverview($tanggal, $kelasId = null)
    {
        // Jika user adalah guru, hanya hitung statistik dari kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');

            $totalSiswa = Siswa::whereIn('kelas_id', $kelasIds)->count();
            $totalKelas = $kelasIds->count();

            $hadirHariIni = Absensi::whereIn('kelas_id', $kelasIds)
                ->where('tanggal', $tanggal)
                ->where('status', 'Hadir')
                ->count();
            $izinHariIni = Absensi::whereIn('kelas_id', $kelasIds)
                ->where('tanggal', $tanggal)
                ->where('status', 'Izin')
                ->count();
            $sakitHariIni = Absensi::whereIn('kelas_id', $kelasIds)
                ->where('tanggal', $tanggal)
                ->where('status', 'Sakit')
                ->count();
        } else {
            // Admin melihat semua statistik
            $totalSiswa = Siswa::count();
            $totalKelas = Kelas::count();

            $hadirHariIni = Absensi::where('tanggal', $tanggal)
                ->where('status', 'Hadir')
                ->count();
            $izinHariIni = Absensi::where('tanggal', $tanggal)
                ->where('status', 'Izin')
                ->count();
            $sakitHariIni = Absensi::where('tanggal', $tanggal)
                ->where('status', 'Sakit')
                ->count();
        }

        $alphaHariIni = $totalSiswa - ($hadirHariIni + $izinHariIni + $sakitHariIni);

        return [
            'total_siswa' => $totalSiswa,
            'total_kelas' => $totalKelas,
            'hadir_hari_ini' => $hadirHariIni,
            'izin_hari_ini' => $izinHariIni,
            'sakit_hari_ini' => $sakitHariIni,
            'alpha_hari_ini' => $alphaHariIni,
            'persen_hadir' => $totalSiswa > 0 ? round(($hadirHariIni / $totalSiswa) * 100, 1) : 0,
        ];
    }

    /**
     * Calculate school days (excluding weekends)
     */
    private function getHariSekolah($startDate, $endDate)
    {
        $hariSekolah = 0;
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Exclude weekends (Saturday = 6, Sunday = 7)
            if ($currentDate->dayOfWeek !== 6 && $currentDate->dayOfWeek !== 0) {
                $hariSekolah++;
            }
            $currentDate->addDay();
        }

        return $hariSekolah;
    }

    /**
     * Export rekap to PDF
     */
    public function exportPdf(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());
        $kelasId = $request->get('kelas_id');
        $type = $request->get('type', 'harian');

        // Validasi akses untuk guru
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;

            if ($kelasId) {
                $kelas = Kelas::where('id', $kelasId)
                    ->where('wali_kelas_id', $guru->id)
                    ->first();

                if (!$kelas) {
                    return redirect()->route('rekap.index')
                        ->with('error', 'Anda tidak memiliki akses ke kelas ini.');
                }
            }
        }

        if ($type === 'harian') {
            $rekap = $this->getRekapHarian($tanggal, $kelasId);
            $title = "Rekap Absensi Harian - " . Carbon::parse($tanggal)->format('d/m/Y');
        } else {
            $bulan = $request->get('bulan', now()->format('Y-m'));
            $rekap = $this->getRekapBulanan($bulan, $kelasId);
            $title = "Rekap Absensi Bulanan - " . Carbon::parse($bulan)->format('F Y');
        }

        // In a real application, you would use a PDF library like DomPDF
        // For now, we'll return a view that can be printed
        return view('rekap.export', compact('rekap', 'title', 'type', 'tanggal'));
    }

    /**
     * Get detail absensi per siswa
     */
    public function detailSiswa(Request $request, $kelasId)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());

        // Validasi akses untuk guru
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;
            $kelas = Kelas::where('id', $kelasId)
                ->where('wali_kelas_id', $guru->id)
                ->firstOrFail();
        } else {
            $kelas = Kelas::findOrFail($kelasId);
        }

        $kelas->load([
            'siswa' => function ($query) use ($tanggal) {
                $query->with([
                    'absensi' => function ($q) use ($tanggal) {
                        $q->where('tanggal', $tanggal);
                    }
                ])->orderBy('nama_lengkap');
            }
        ]);

        $siswaWithAbsensi = [];
        foreach ($kelas->siswa as $siswa) {
            $absensi = $siswa->absensi->first();
            $siswaWithAbsensi[] = [
                'siswa' => $siswa,
                'status' => $absensi ? $absensi->status : 'Alpha',
                'waktu_absen' => $absensi ? $absensi->waktu_absen : null,
                'metode_absen' => $absensi ? $absensi->metode_absen : null,
            ];
        }

        return view('rekap.detail-siswa', compact('kelas', 'siswaWithAbsensi', 'tanggal'));
    }
}