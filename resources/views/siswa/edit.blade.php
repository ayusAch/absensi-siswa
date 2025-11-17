@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Siswa</h1>

        <!-- Notifikasi Error -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Edit Siswa -->
            <div class="lg:col-span-2">
                <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" class="bg-white shadow rounded-lg p-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label for="nama_lengkap" class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap"
                            value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan nama lengkap" required>
                    </div>

                    <!-- Kelas -->
                    <div class="mb-4">
                        <label for="kelas_id" class="block text-gray-700 font-semibold mb-2">Kelas</label>
                        <select name="kelas_id" id="kelas_id"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach(App\Models\Kelas::all() as $kelas)
                                <option value="{{ $kelas->id }}" {{ old('kelas_id', $siswa->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-4">
                        <label for="jenis_kelamin" class="block text-gray-700 font-semibold mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-6">
                        <label for="alamat" class="block text-gray-700 font-semibold mb-2">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="4"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan alamat" required>{{ old('alamat', $siswa->alamat) }}</textarea>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-between">
                        <a href="{{ route('siswa.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Kembali
                        </a>
                        <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded">
                            Update Data Siswa
                        </button>
                    </div>
                </form>
            </div>

            <!-- Panel QR Code -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">QR Code Siswa</h2>
                    
                    @if($siswa->qr_code)
                        <!-- Tampilkan QR Code -->
                        <div class="text-center mb-4">
                            <img src="{{ $siswa->qr_code }}" 
                                 alt="QR Code {{ $siswa->nama_lengkap }}" 
                                 class="w-48 h-48 mx-auto border rounded-lg">
                            <p class="text-sm text-gray-600 mt-2">ID: {{ $siswa->id }}</p>
                        </div>

                        <!-- Tombol Aksi QR Code -->
                        <div class="space-y-3">
                            <a href="{{ route('siswa.download.qr', $siswa->id) }}" 
                               class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download QR Code
                            </a>
                            
                            <a href="{{ route('siswa.regenerate.qr', $siswa->id) }}" 
                               onclick="return confirm('Apakah yakin ingin generate ulang QR Code? QR Code lama akan diganti.')"
                               class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Generate Ulang QR
                            </a>
                        </div>
                    @else
                        <!-- Jika belum ada QR Code -->
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-6xl mb-4">❓</div>
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

                    <!-- Informasi -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-800 text-sm mb-2">Informasi QR Code:</h3>
                        <ul class="text-blue-700 text-xs space-y-1">
                            <li>• QR Code berisi ID siswa: {{ $siswa->id }}</li>
                            <li>• Dapat digunakan untuk absensi</li>
                            <li>• Download untuk dicetak</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection