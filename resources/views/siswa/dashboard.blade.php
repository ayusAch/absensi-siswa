@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Siswa</h1>

        @if($siswa)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Profil Saya</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Nama</p>
                        <p class="font-semibold">{{ $siswa->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Kelas</p>
                        <p class="font-semibold">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">NIS</p>
                        <p class="font-semibold">{{ $siswa->nis ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Absensi Terbaru</h2>
                @if($absensiTerbaru->count() > 0)
                    <div class="space-y-3">
                        @foreach($absensiTerbaru as $absensi)
                            <div class="flex justify-between items-center p-3 border rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $absensi->tanggal }}</p>
                                    <p class="text-sm text-gray-600">{{ $absensi->status }}</p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-sm 
                                        {{ $absensi->status == 'Hadir' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $absensi->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada riwayat absensi</p>
                @endif
            </div>
        @else
            <div class="bg-yellow-100 border border-yellow-400 rounded-lg p-4">
                <p>Data siswa tidak ditemukan. Hubungi administrator.</p>
            </div>
        @endif
    </div>
@endsection