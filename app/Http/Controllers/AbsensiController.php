<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Jika user adalah guru, hanya tampilkan absensi dari kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;

            // Pastikan guru ditemukan
            if (!$guru) {
                return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
            }

            $absensi = Absensi::whereHas('siswa.kelas', function ($query) use ($guru) {
                $query->where('wali_kelas_id', $guru->id);
            })
                ->with(['siswa', 'kelas'])
                ->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Admin bisa lihat semua absensi
            $absensi = Absensi::with(['siswa', 'kelas'])
                ->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('absensi.index', compact('absensi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Jika user adalah guru, hanya tampilkan kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;

            if (!$guru) {
                return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
            }

            $kelas = Kelas::where('wali_kelas_id', $guru->id)->get();
            $siswa = Siswa::whereIn('kelas_id', $kelas->pluck('id'))->with('kelas')->get();
        } else {
            // Admin bisa lihat semua
            $kelas = Kelas::all();
            $siswa = Siswa::with('kelas')->get();
        }

        return view('absensi.create', compact('siswa', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
        ]);

        // Validasi tambahan untuk guru - pastikan siswa berasal dari kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;
            $siswa = Siswa::with('kelas')->findOrFail($request->siswa_id);

            if ($siswa->kelas->wali_kelas_id !== $guru->id) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk melakukan absensi pada siswa ini.');
            }
        }

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

        // Ambil kelas_id dari siswa
        $siswa = Siswa::findOrFail($request->siswa_id);

        $absensi = Absensi::create([
            'siswa_id' => $request->siswa_id,
            'kelas_id' => $siswa->kelas_id,
            'tanggal' => $tanggal,
            'status' => $request->status,
            'waktu_absen' => now(),
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

        // Validasi untuk guru - pastikan absensi berasal dari kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;
            if ($absensi->kelas->wali_kelas_id !== $guru->id) {
                return redirect()->route('absensi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat absensi ini.');
            }
        }

        return view('absensi.show', compact('absensi'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $absensi = Absensi::with('kelas')->findOrFail($id);

        // Validasi untuk guru - pastikan absensi berasal dari kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;
            if ($absensi->kelas->wali_kelas_id !== $guru->id) {
                return redirect()->route('absensi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus absensi ini.');
            }
        }

        $absensi->delete();

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus');
    }

    /**
     * Scan QR Code untuk absensi
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

            // Validasi untuk guru - pastikan siswa berasal dari kelas yang diampu
            if (auth()->user()->isGuru()) {
                $guru = auth()->user()->guru;
                if ($siswa->kelas->wali_kelas_id !== $guru->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses untuk melakukan absensi pada siswa ini.'
                    ], 403);
                }
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
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman scanner QR Code
     */
    public function scanner()
    {
        return view('absensi.scanner');
    }

    /**
     * Rekap absensi harian
     */
    public function rekapHarian(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());

        // Jika user adalah guru, hanya tampilkan kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;

            if (!$guru) {
                return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
            }

            $kelas = Kelas::where('wali_kelas_id', $guru->id)->get();
            $kelasIds = $kelas->pluck('id');

            $absensi = Absensi::with(['siswa', 'kelas'])
                ->whereIn('kelas_id', $kelasIds)
                ->where('tanggal', $tanggal)
                ->orderBy('kelas_id')
                ->orderBy('siswa_id')
                ->get();

            $totalSiswa = Siswa::whereIn('kelas_id', $kelasIds)->count();

        } else {
            // Admin bisa lihat semua
            $kelas = Kelas::all();
            $absensi = Absensi::with(['siswa', 'kelas'])
                ->where('tanggal', $tanggal)
                ->orderBy('kelas_id')
                ->orderBy('siswa_id')
                ->get();

            $totalSiswa = Siswa::count();
        }

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
            'totalSiswa',
            'kelas'
        ));
    }

    /**
     * Get siswa by kelas untuk dropdown
     */
    public function getSiswaByKelas($kelasId)
    {
        // Validasi untuk guru - pastikan kelas yang diakses adalah kelas yang diampu
        if (auth()->user()->isGuru()) {
            $guru = auth()->user()->guru;
            $kelas = Kelas::where('id', $kelasId)
                ->where('wali_kelas_id', $guru->id)
                ->first();

            if (!$kelas) {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }
        }

        $siswa = Siswa::where('kelas_id', $kelasId)->get();
        return response()->json($siswa);
    }
}