<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // GANTI: Pakai manual loading dulu
        $siswa = Siswa::with('kelas')->get();

        // Manual load user untuk setiap siswa
        $siswa->load('user');

        return view('siswa.index', compact('siswa'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
        ]);

        // Simpan data siswa
        $siswa = Siswa::create([
            'nama_lengkap' => $request->nama_lengkap,
            'kelas_id' => $request->kelas_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ]);

        // Generate QR Code menggunakan Bacon QR Code
        $qrCodeData = $this->generateQrCode($siswa->id);

        // Update database siswa dengan QR code base64
        $siswa->update([
            'qr_code' => $qrCodeData
        ]);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan dengan QR code.');
    }

    /**
     * Generate QR Code untuk siswa menggunakan Bacon QR Code
     */
    private function generateQrCode($siswaId)
    {
        try {
            $data = "SISWA:" . $siswaId;

            $renderer = new ImageRenderer(
                new RendererStyle(200),
                new ImagickImageBackEnd()
            );

            $writer = new Writer($renderer);
            $qrCodeImage = $writer->writeString($data);

            return 'data:image/png;base64,' . base64_encode($qrCodeImage);

        } catch (\Exception $e) {
            return $this->generateQrCodeSimple($siswaId);
        }
    }

    /**
     * Fallback method jika Imagick tidak tersedia
     */
    private function generateQrCodeSimple($siswaId)
    {
        $data = "SISWA:" . $siswaId;
        $url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($data);

        try {
            $imageContent = file_get_contents($url);
            return 'data:image/png;base64,' . base64_encode($imageContent);
        } catch (\Exception $e) {
            \Log::error('QR Code generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        return view('siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswa.edit', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
        ]);

        $siswa->update([
            'nama_lengkap' => $request->nama_lengkap,
            'kelas_id' => $request->kelas_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }

    /**
     * Download QR Code
     */
    public function downloadQrCode($id)
    {
        $siswa = Siswa::findOrFail($id);

        $data = "SISWA:" . $siswa->id;
        $url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($data);

        try {
            $imageContent = file_get_contents($url);
            $fileName = 'qr-code-' . str_replace(' ', '-', $siswa->nama_lengkap) . '.png';

            return response($imageContent)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mendownload QR Code.');
        }
    }

    // ==================== USER MANAGEMENT METHODS ====================

    /**
     * Create user account for siswa
     */
    public function createUser(Siswa $siswa)
    {
        // Cek apakah sudah ada user untuk siswa ini
        if ($siswa->user) {
            return redirect()->route('siswa.index') // PERBAIKI: redirect ke index
                ->with('error', 'Siswa ini sudah memiliki akun user.');
        }

        return view('siswa.create-user', compact('siswa'));
    }

    /**
     * Store user account for siswa
     */
    public function storeUser(Request $request, Siswa $siswa)
    {
        // Validasi
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Buat user
            $user = User::create([
                'name' => $siswa->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
                'siswa_id' => $siswa->id,
            ]);

            return redirect()->route('siswa.index') // PERBAIKI: redirect ke index
                ->with('success', 'Akun user berhasil dibuat untuk siswa ' . $siswa->nama_lengkap);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }

    /**
     * Edit user account for siswa
     */
    public function editUser(Siswa $siswa)
    {
        if (!$siswa->user) {
            return redirect()->route('siswa.index') // PERBAIKI: redirect ke index
                ->with('error', 'Siswa ini belum memiliki akun user.');
        }

        return view('siswa.edit-user', compact('siswa'));
    }

    /**
     * Update user account for siswa
     */
    public function updateUser(Request $request, Siswa $siswa)
    {
        if (!$siswa->user) {
            return redirect()->route('siswa.index') // PERBAIKI: redirect ke index
                ->with('error', 'Siswa ini belum memiliki akun user.');
        }

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $siswa->user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            $updateData = [
                'name' => $siswa->nama_lengkap,
                'email' => $request->email,
            ];

            if ($request->password) {
                $updateData['password'] = Hash::make($request->password);
            }

            $siswa->user->update($updateData);

            return redirect()->route('siswa.index') // PERBAIKI: redirect ke index
                ->with('success', 'Akun user berhasil diupdate untuk siswa ' . $siswa->nama_lengkap);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal update akun: ' . $e->getMessage());
        }
    }

    /**
     * Destroy user account for siswa
     */
    public function destroyUser(Siswa $siswa)
    {
        if (!$siswa->user) {
            return redirect()->route('siswa.index') // PERBAIKI: redirect ke index
                ->with('error', 'Siswa ini belum memiliki akun user.');
        }

        try {
            $siswa->user->delete();

            return redirect()->route('siswa.index') // PERBAIKI: redirect ke index
                ->with('success', 'Akun user berhasil dihapus untuk siswa ' . $siswa->nama_lengkap);

        } catch (\Exception $e) {
            return redirect()->route('siswa.index') // PERBAIKI: redirect ke index
                ->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }

    /**
     * Generate random password for siswa
     */
    public function generatePassword(Siswa $siswa)
    {
        $randomPassword = Str::random(8);

        try {
            if ($siswa->user) {
                // Update password existing user
                $siswa->user->update([
                    'password' => Hash::make($randomPassword)
                ]);
            } else {
                // Buat user baru
                $user = User::create([
                    'name' => $siswa->nama_lengkap,
                    'email' => $siswa->email ?? strtolower(str_replace(' ', '.', $siswa->nama_lengkap)) . '@siswa.school.id',
                    'password' => Hash::make($randomPassword),
                    'role' => 'siswa',
                    'siswa_id' => $siswa->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'password' => $randomPassword,
                'message' => 'Password berhasil digenerate'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate password: ' . $e->getMessage()
            ], 500);
        }
    }
}