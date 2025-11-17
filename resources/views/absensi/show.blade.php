<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Absensi - Sistem Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6 max-w-4xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">👁️ Detail Absensi</h1>
                <p class="text-gray-600 mt-2">Informasi lengkap data absensi</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('absensi.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    ← Kembali
                </a>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Informasi Absensi</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Siswa Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Data Siswa</h3>

                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium text-gray-700">Nama Siswa:</span>
                            <span class="text-gray-900">{{ $absensi->siswa->nama_lengkap }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium text-gray-700">NIS:</span>
                            <span class="text-gray-900">{{ $absensi->siswa->nis ?? '-' }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium text-gray-700">Kelas:</span>
                            <span class="text-gray-900">{{ $absensi->kelas->nama_kelas }}</span>
                        </div>
                    </div>

                    <!-- Absensi Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Data Absensi</h3>

                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium text-gray-700">Tanggal:</span>
                            <span
                                class="text-gray-900">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium text-gray-700">Status:</span>
                            @php
                                $statusColors = [
                                    'Hadir' => 'bg-green-100 text-green-800',
                                    'Izin' => 'bg-yellow-100 text-yellow-800',
                                    'Sakit' => 'bg-blue-100 text-blue-800',
                                    'Alpha' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span
                                class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$absensi->status] }}">
                                {{ $absensi->status }}
                            </span>
                        </div>

                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium text-gray-700">Waktu Absen:</span>
                            <span class="text-gray-900">
                                @if($absensi->waktu_absen)
                                    {{ \Carbon\Carbon::parse($absensi->waktu_absen)->format('H:i:s') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium text-gray-700">Metode Absen:</span>
                            <span class="text-gray-900">{{ $absensi->metode_absen ?? 'Manual' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t flex justify-end space-x-3">
                    <a href="{{ route('absensi.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        Kembali
                    </a>
                    <form action="{{ route('absensi.destroy', $absensi->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition duration-200"
                            onclick="return confirm('Hapus data absensi ini?')">
                            Hapus Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>