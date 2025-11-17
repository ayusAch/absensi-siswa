<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Sistem Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 20px;
            }

            .page-break {
                page-break-after: always;
            }
        }

        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-white text-gray-800">
    <!-- Header untuk Print -->
    <div class="no-print fixed top-0 left-0 right-0 bg-blue-600 text-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">Sistem Absensi - Export Laporan</h1>
            <button onclick="window.print()" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold">
                🖨️ Cetak Laporan
            </button>
        </div>
    </div>

    <div class="container mx-auto p-6 max-w-6xl mt-16">
        <!-- Header Laporan -->
        <div class="text-center mb-8 border-b-2 border-gray-300 pb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $title }}</h1>
            <p class="text-gray-600 text-lg">Sistem Absensi Digital</p>
            <p class="text-gray-500 text-sm">
                Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}
            </p>
        </div>

        @if($type === 'harian')
            <!-- Rekap Harian -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 bg-gray-100 p-3 rounded-lg">
                    📊 Rekap Harian - {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
                </h2>

                <table class="min-w-full border border-gray-300 mb-6">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">No</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Kelas</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Total Siswa</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Hadir</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Izin</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Sakit</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Alpha</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">% Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekap as $key => $r)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border border-gray-300 px-4 py-3">{{ $loop->iteration }}</td>
                                        <td class="border border-gray-300 px-4 py-3 font-medium">{{ $r['kelas'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $r['total_siswa'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center text-green-600 font-semibold">
                                            {{ $r['Hadir'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center text-yellow-600">{{ $r['Izin'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center text-blue-600">{{ $r['Sakit'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center text-red-600">{{ $r['Alpha'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center font-semibold 
                                            {{ $r['persen_hadir'] >= 80 ? 'text-green-600' :
                            ($r['persen_hadir'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $r['persen_hadir'] }}%
                                        </td>
                                    </tr>
                        @endforeach
                    </tbody>
                    @if(count($rekap) > 0)
                            @php
                                $totalSiswa = array_sum(array_column($rekap, 'total_siswa'));
                                $totalHadir = array_sum(array_column($rekap, 'Hadir'));
                                $totalIzin = array_sum(array_column($rekap, 'Izin'));
                                $totalSakit = array_sum(array_column($rekap, 'Sakit'));
                                $totalAlpha = array_sum(array_column($rekap, 'Alpha'));
                                $totalPersen = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;
                            @endphp
                            <tfoot class="bg-gray-100 font-semibold">
                                <tr>
                                    <td class="border border-gray-300 px-4 py-3 text-center" colspan="2">TOTAL</td>
                                    <td class="border border-gray-300 px-4 py-3 text-center">{{ $totalSiswa }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-center text-green-600">{{ $totalHadir }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-center text-yellow-600">{{ $totalIzin }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-center text-blue-600">{{ $totalSakit }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-center text-red-600">{{ $totalAlpha }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-center 
                                        {{ $totalPersen >= 80 ? 'text-green-600' :
                        ($totalPersen >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $totalPersen }}%
                                    </td>
                                </tr>
                            </tfoot>
                    @endif
                </table>
            </div>

            <!-- Statistik Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="border border-gray-300 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">📈 Statistik Kehadiran</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Total Siswa:</span>
                            <span class="font-semibold">{{ $totalSiswa }}</span>
                        </div>
                        <div class="flex justify-between text-green-600">
                            <span>Hadir:</span>
                            <span class="font-semibold">{{ $totalHadir }}
                                ({{ round(($totalHadir / $totalSiswa) * 100, 1) }}%)</span>
                        </div>
                        <div class="flex justify-between text-yellow-600">
                            <span>Izin:</span>
                            <span class="font-semibold">{{ $totalIzin }}
                                ({{ round(($totalIzin / $totalSiswa) * 100, 1) }}%)</span>
                        </div>
                        <div class="flex justify-between text-blue-600">
                            <span>Sakit:</span>
                            <span class="font-semibold">{{ $totalSakit }}
                                ({{ round(($totalSakit / $totalSiswa) * 100, 1) }}%)</span>
                        </div>
                        <div class="flex justify-between text-red-600">
                            <span>Alpha:</span>
                            <span class="font-semibold">{{ $totalAlpha }}
                                ({{ round(($totalAlpha / $totalSiswa) * 100, 1) }}%)</span>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-300 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">🏆 Keterangan</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            <span>≥ 80%: Kehadiran Sangat Baik</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                            <span>60-79%: Kehadiran Cukup</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                            <span>&lt; 60%: Perlu Perhatian</span>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- Rekap Bulanan -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 bg-gray-100 p-3 rounded-lg">
                    📅 Rekap Bulanan - {{ \Carbon\Carbon::parse($bulan ?? now()->format('Y-m'))->format('F Y') }}
                </h2>

                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Kelas</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Total Siswa</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Hari Sekolah</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Total Kehadiran</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Hadir</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Izin</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Sakit</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold">% Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekap as $r)
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-3 font-medium">{{ $r['kelas'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $r['total_siswa'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $r['hari_sekolah'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $r['total_kehadiran'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center text-green-600 font-semibold">
                                            {{ $r['Hadir'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center text-yellow-600">{{ $r['Izin'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center text-blue-600">{{ $r['Sakit'] }}</td>
                                        <td class="border border-gray-300 px-4 py-3 text-center font-semibold 
                                            {{ $r['persen_hadir'] >= 80 ? 'text-green-600' :
                            ($r['persen_hadir'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $r['persen_hadir'] }}%
                                        </td>
                                    </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Footer -->
        <div class="mt-12 pt-6 border-t border-gray-300 text-center text-sm text-gray-500">
            <p>Laporan ini dibuat secara otomatis oleh Sistem Absensi Digital</p>
            <p>© {{ date('Y') }} - Semua hak dilindungi undang-undang</p>
        </div>
    </div>

    <script>
        // Auto print ketika halaman load (opsional)
        window.onload = function () {
            // Uncomment baris berikut untuk auto print
            // window.print();
        };
    </script>
</body>

</html>