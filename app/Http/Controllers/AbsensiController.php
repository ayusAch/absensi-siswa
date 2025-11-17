<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan semua absensi dengan relasi siswa dan kelas
        $absensi = Absensi::with(['siswa', 'kelas'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('absensi.index', compact('absensi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswa = Siswa::with('kelas')->get();
        return view('absensi.create', compact('siswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
        ]);

        $tanggal = now()->toDateString();

        // Cek apakah siswa sudah diabsen hari ini
        $exists = Absensi::where('siswa_id', $request->siswa_id)
            ->where('tanggal', $tanggal)
            ->exists();

        if ($exists) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Siswa sudah diabsen hari ini'], 400);
            }
            return redirect()->back()->with('error', 'Siswa sudah diabsen hari ini');
        }

        $absensi = Absensi::create([
            'siswa_id' => $request->siswa_id,
            'kelas_id' => $request->kelas_id,
            'tanggal' => $tanggal,
            'status' => $request->status,
            'waktu_absen' => now(), // Tambahkan timestamp absensi
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Absensi berhasil dicatat',
                'data' => $absensi->load('siswa')
            ]);
        }

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dicatat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $absensi = Absensi::with(['siswa', 'kelas'])->findOrFail($id);
        return view('absensi.show', compact('absensi'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus');
    }

    /**
     * NEW METHOD: Scan QR Code untuk absensi
     */

    public function scanQrCode(Request $request)
    {
        try {
            $request->validate([
                'qr_data' => 'required|string',
            ]);

            // Format QR data: "SISWA:123"
            if (!str_starts_with($request->qr_data, 'SISWA:')) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid. Format harus: SISWA:ID'
                ], 400);
            }

            $siswaId = str_replace('SISWA:', '', $request->qr_data);

            // Cari siswa berdasarkan ID
            $siswa = Siswa::with('kelas')->find($siswaId);

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan dengan ID: ' . $siswaId
                ], 404);
            }

            $tanggal = now()->toDateString();

            // Cek apakah sudah absen hari ini
            $alreadyAbsent = Absensi::where('siswa_id', $siswaId)
                ->where('tanggal', $tanggal)
                ->exists();

            if ($alreadyAbsent) {
                return response()->json([
                    'success' => false,
                    'message' => $siswa->nama_lengkap . ' sudah absen hari ini'
                ], 400);
            }

            // Buat record absensi
            $absensi = Absensi::create([
                'siswa_id' => $siswaId,
                'kelas_id' => $siswa->kelas_id,
                'tanggal' => $tanggal,
                'status' => 'Hadir',
                'waktu_absen' => now(),
                'metode_absen' => 'QR Code',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil: ' . $siswa->nama_lengkap,
                'data' => [
                    'siswa' => $siswa->nama_lengkap,
                    'kelas' => $siswa->kelas->nama_kelas,
                    'waktu' => $absensi->waktu_absen->format('H:i:s'),
                    'status' => 'Hadir'
                ]
            ]);

        } catch (\Exception $e) {
            // Pastikan selalu return JSON bahkan ketika error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * NEW METHOD: Halaman scanner QR Code
     */
    public function scanner()
    {
        return view('absensi.scanner');
    }

    /**
     * NEW METHOD: Rekap absensi harian
     */
    public function rekapHarian(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());

        $absensi = Absensi::with(['siswas', 'kelas'])
            ->where('tanggal', $tanggal)
            ->orderBy('kelas_id')
            ->orderBy('siswa_id')
            ->get();

        $totalSiswa = Siswa::count();
        $hadir = $absensi->where('status', 'Hadir')->count();
        $izin = $absensi->where('status', 'Izin')->count();
        $sakit = $absensi->where('status', 'Sakit')->count();
        $alpha = $totalSiswa - ($hadir + $izin + $sakit);

        return view('absensi.rekap-harian', compact(
            'absensi',
            'tanggal',
            'hadir',
            'izin',
            'sakit',
            'alpha',
            'totalSiswa'
        ));
    }

    /**
     * NEW METHOD: Get siswa by kelas untuk dropdown
     */
    public function getSiswaByKelas($kelasId)
    {
        $siswa = Siswa::where('kelas_id', $kelasId)->get();
        return response()->json($siswa);
    }
}