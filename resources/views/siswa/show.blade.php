@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-4xl">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Detail Siswa</h1>
            <a href="{{ route('siswa.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informasi Siswa -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Siswa</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                        <p class="mt-1 text-lg text-gray-800">{{ $siswa->nama_lengkap }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Kelas</label>
                        <p class="mt-1 text-lg text-gray-800">{{ $siswa->kelas->nama_kelas }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Jenis Kelamin</label>
                        <p class="mt-1 text-lg text-gray-800">{{ $siswa->jenis_kelamin }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Alamat</label>
                        <p class="mt-1 text-gray-800">{{ $siswa->alamat }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">ID Siswa</label>
                        <p class="mt-1 font-mono text-gray-600">SISWA:{{ $siswa->id }}</p>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-6 flex space-x-3">
                    <a href="{{ route('siswa.edit', $siswa->id) }}" 
                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Data
                    </a>
                    
                    <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" 
                          onsubmit="return confirm('Apakah yakin ingin menghapus siswa ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">QR Code Siswa</h2>
                
                @if($siswa->qr_code)
                    <div class="text-center">
                        <!-- QR Code Display -->
                        <div class="inline-block border-4 border-gray-200 rounded-lg p-4 mb-4">
                            <img src="{{ $siswa->qr_code }}" 
                                 alt="QR Code {{ $siswa->nama_lengkap }}" 
                                 class="w-64 h-64 mx-auto">
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-2">Scan QR code untuk informasi siswa</p>
                        <p class="font-mono text-xs text-gray-500 bg-gray-100 p-2 rounded">SISWA:{{ $siswa->id }}</p>

                        <!-- Tombol Aksi QR Code -->
                        <div class="mt-6 space-y-3">
                            <a href="{{ route('siswa.download.qr', $siswa->id) }}" 
                               class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg flex items-center justify-center font-semibold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download QR Code (PNG)
                            </a>
                            
                            <a href="{{ route('siswa.regenerate.qr', $siswa->id) }}" 
                               onclick="return confirm('Apakah yakin ingin generate ulang QR Code? QR Code lama akan diganti.')"
                               class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Generate Ulang QR Code
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Jika belum ada QR Code -->
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-6xl mb-4">📷</div>
                        <p class="text-gray-500 mb-4">QR Code belum tersedia</p>
                        <a href="{{ route('siswa.regenerate.qr', $siswa->id) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Generate QR Code
                        </a>
                    </div>
                @endif

                <!-- Informasi QR Code -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-semibold text-blue-800 text-sm mb-2">Fungsi QR Code:</h3>
                    <ul class="text-blue-700 text-xs space-y-1">
                        <li>• Digunakan untuk sistem absensi</li>
                        <li>• Berisi ID unik siswa: {{ $siswa->id }}</li>
                        <li>• Dapat di-download untuk dicetak</li>
                        <li>• Scan dengan aplikasi QR reader</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection