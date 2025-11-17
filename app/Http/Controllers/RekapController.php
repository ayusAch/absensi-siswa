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

        $kelasList = Kelas::withCount('siswa')->get();

        // Rekap Harian
        $rekapHarian = $this->getRekapHarian($tanggal, $kelasId);

        // Rekap Bulanan
        $rekapBulanan = $this->getRekapBulanan($bulan, $kelasId);

        // Statistik Overview
        $statistik = $this->getStatistikOverview($tanggal);

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
        $query = Kelas::withCount('siswa');

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

        $query = Kelas::withCount('siswa');

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
    private function getStatistikOverview($tanggal)
    {
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

        $kelas = Kelas::with([
            'siswa' => function ($query) use ($tanggal) {
                $query->with([
                    'absensi' => function ($q) use ($tanggal) {
                        $q->where('tanggal', $tanggal);
                    }
                ])->orderBy('nama_lengkap');
            }
        ])->findOrFail($kelasId);

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