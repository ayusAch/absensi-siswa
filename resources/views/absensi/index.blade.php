<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi - Sistem Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">📊 Data Absensi</h1>
                <p class="text-gray-600 mt-2">Manajemen data absensi siswa</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('absensi.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    + Input Manual
                </a>
                <a href="{{ route('absensi.scanner') }}" 
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    📱 Scan QR
                </a>
                <a href="{{ route('absensi.rekap-harian') }}" 
                   class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    📋 Rekap Harian
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Total Record</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $absensi->total() }}</p>
                    </div>
                    <div class="text-blue-500 text-2xl">📄</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Hadir Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Absensi::where('tanggal', today()->toDateString())->where('status', 'Hadir')->count() }}
                        </p>
                    </div>
                    <div class="text-green-500 text-2xl">✅</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Izin/Sakit</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Absensi::where('tanggal', today()->toDateString())->whereIn('status', ['Izin', 'Sakit'])->count() }}
                        </p>
                    </div>
                    <div class="text-yellow-500 text-2xl">⚠️</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Alpha Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Siswa::count() - \App\Models\Absensi::where('tanggal', today()->toDateString())->count() }}
                        </p>
                    </div>
                    <div class="text-red-500 text-2xl">❌</div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Riwayat Absensi</h2>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 m-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 m-4 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Siswa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu Absen
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($absensi as $record)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $record->siswa->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">NIS: {{ $record->siswa->nis ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $record->kelas->nama_kelas }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($record->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'Hadir' => 'bg-green-100 text-green-800',
                                        'Izin' => 'bg-yellow-100 text-yellow-800',
                                        'Sakit' => 'bg-blue-100 text-blue-800',
                                        'Alpha' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$record->status] }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                @if($record->waktu_absen)
                                    {{ \Carbon\Carbon::parse($record->waktu_absen)->format('H:i:s') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $record->metode_absen ?? 'Manual' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('absensi.show', $record->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                <form action="{{ route('absensi.destroy', $record->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Hapus data absensi ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="text-4xl mb-2">📊</div>
                                <p>Belum ada data absensi</p>
                                <a href="{{ route('absensi.create') }}" class="text-blue-500 hover:text-blue-700 mt-2 inline-block">
                                    Input absensi pertama →
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($absensi->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $absensi->links() }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>