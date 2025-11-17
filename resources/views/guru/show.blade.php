@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Guru</h1>
            <p class="text-gray-600 mt-2">Informasi lengkap {{ $guru->nama_lengkap }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('guru.edit', $guru->id) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center">
                ✏️ Edit
            </a>
            <a href="{{ route('guru.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                ↩️ Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informasi Guru -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Pribadi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                            <p class="mt-1 text-lg font-semibold text-gray-800">{{ $guru->nama_lengkap }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">NIP</label>
                            <p class="mt-1 text-gray-800">{{ $guru->nip ?? '<span class="text-gray-400">-</span>' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <p class="mt-1 text-gray-800">{{ $guru->email }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Jenis Kelamin</label>
                            <p class="mt-1 text-gray-800">{{ $guru->jenis_kelamin }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Tanggal Lahir</label>
                            <p class="mt-1 text-gray-800">
                                @if($guru->tanggal_lahir)
                                    {{ \Carbon\Carbon::parse($guru->tanggal_lahir)->translatedFormat('d F Y') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Status</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $guru->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $guru->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-600">Alamat</label>
                    <p class="mt-1 text-gray-800">{{ $guru->alamat ?? '<span class="text-gray-400">-</span>' }}</p>
                </div>

                <!-- Keterangan -->
                @if($guru->keterangan)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-600">Keterangan</label>
                    <p class="mt-1 text-gray-800">{{ $guru->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Info Kontak -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Kontak</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs text-gray-500">No. Telepon</label>
                        <p class="text-sm text-gray-800">{{ $guru->no_telepon ?? '<span class="text-gray-400">-</span>' }}</p>
                    </div>
                </div>
            </div>

            <!-- Kelas yang Diampu -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Kelas yang Diampu</h2>
                @if($guru->kelas->count() > 0)
                    <div class="space-y-2">
                        @foreach($guru->kelas as $kelas)
                        <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                            <span class="text-sm font-medium text-blue-800">{{ $kelas->nama_kelas }}</span>
                            <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">
                                {{ $kelas->siswa_count ?? 0 }} siswa
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">Belum menjadi wali kelas</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection