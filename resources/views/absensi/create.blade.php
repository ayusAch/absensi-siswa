<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Absensi Manual - Sistem Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6 max-w-2xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">📝 Input Absensi Manual</h1>
                <p class="text-gray-600 mt-2">Input data absensi siswa secara manual</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('absensi.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    ← Kembali
                </a>
                <a href="{{ route('absensi.scanner') }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    📱 Scan QR
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg p-6">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('absensi.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Siswa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Siswa *</label>
                        <select name="siswa_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Siswa</option>
                            @foreach($siswa as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->nama_lengkap }} - {{ $s->kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas *</label>
                        <select name="kelas_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kelas</option>
                            @foreach($siswa->unique('kelas_id') as $s)
                                <option value="{{ $s->kelas->id }}">
                                    {{ $s->kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Kehadiran *</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach(['Hadir', 'Izin', 'Sakit', 'Alpha'] as $status)
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="status" value="{{ $status }}" class="mr-3 text-blue-500"
                                        required>
                                    <span class="font-medium">{{ $status }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('absensi.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        Simpan Absensi
                    </button>
                </div>
            </form>
        </div>

        <!-- Info -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="text-blue-500 text-xl mr-3">💡</div>
                <div>
                    <h3 class="font-semibold text-blue-800">Tips Input Manual</h3>
                    <p class="text-blue-700 text-sm mt-1">
                        • Sistem akan otomatis mencatat tanggal hari ini<br>
                        • Pastikan siswa belum melakukan absensi hari ini<br>
                        • Untuk absensi massal, gunakan fitur Scan QR Code
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-fill kelas ketika siswa dipilih
        document.querySelector('select[name="siswa_id"]').addEventListener('change', function () {
            const siswaId = this.value;
            if (siswaId) {
                // Cari data siswa yang dipilih
                const selectedOption = this.options[this.selectedIndex];
                const kelasInfo = selectedOption.text.split(' - ')[1];

                // Set kelas_id otomatis
                const kelasSelect = document.querySelector('select[name="kelas_id"]');
                for (let option of kelasSelect.options) {
                    if (option.text === kelasInfo) {
                        option.selected = true;
                        break;
                    }
                }
            }
        });
    </script>
</body>

</html>