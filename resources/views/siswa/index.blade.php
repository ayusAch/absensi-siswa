@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Siswa</h1>

        <!-- Notifikasi Success -->
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tombol Tambah Siswa -->
        <a href="{{ route('siswa.create') }}"
            class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded mb-4">
            Tambah Siswa
        </a>

        <!-- Tabel Siswa -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($siswa as $key => $s)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $key + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $s->nama_lengkap }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $s->kelas->nama_kelas }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $s->jenis_kelamin }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($s->qr_code)
                                    <div class="flex flex-col items-center space-y-2">
                                        <img src="{{ $s->qr_code }}" 
                                             alt="QR Code {{ $s->nama_lengkap }}" 
                                             class="w-16 h-16 border rounded-lg">
                                        <a href="{{ route('siswa.download.qr', $s->id) }}" 
                                           class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs">
                                            Download
                                        </a>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">No QR</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <a href="{{ route('siswa.show', $s->id) }}"
                                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-sm">Detail</a>
                                    <a href="{{ route('siswa.edit', $s->id) }}"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</a>
                                    <form action="{{ route('siswa.destroy', $s->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah yakin ingin menghapus siswa ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Info jika tidak ada data -->
        @if($siswa->count() == 0)
            <div class="text-center py-8 text-gray-500">
                Belum ada data siswa. 
                <a href="{{ route('siswa.create') }}" class="text-blue-500 hover:text-blue-700">Tambah siswa pertama</a>
            </div>
        @endif
    </div>
@endsection