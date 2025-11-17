<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
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
        $siswa = Siswa::with('kelas')->get();
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
                new RendererStyle(200), // size
                new ImagickImageBackEnd()
            );
            
            $writer = new Writer($renderer);
            $qrCodeImage = $writer->writeString($data);
            
            return 'data:image/png;base64,' . base64_encode($qrCodeImage);
            
        } catch (\Exception $e) {
            // Fallback jika Imagick tidak tersedia
            return $this->generateQrCodeSimple($siswaId);
        }
    }

    /**
     * Fallback method jika Imagick tidak tersedia
     */
    private function generateQrCodeSimple($siswaId)
    {
        $data = "SISWA:" . $siswaId;
        
        // Simple QR code generation tanpa library complex
        // Atau bisa menggunakan Google Charts API sebagai fallback
        $url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($data);
        
        try {
            $imageContent = file_get_contents($url);
            return 'data:image/png;base64,' . base64_encode($imageContent);
        } catch (\Exception $e) {
            // Return null jika semua method gagal
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
}