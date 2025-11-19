@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-2xl">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Buat Akun User untuk Siswa</h2>

            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h3 class="font-semibold text-blue-800 dark:text-blue-300">Informasi Siswa:</h3>
                <p class="text-blue-700 dark:text-blue-400">
                    <strong>NIS:</strong> {{ $siswa->nis }} |
                    <strong>Nama:</strong> {{ $siswa->nama }} |
                    <strong>Kelas:</strong> {{ $siswa->kelas->nama ?? '-' }}
                </p>
            </div>

            <form action="{{ route('siswa.store-user', $siswa) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address
                    </label>
                    <input type="email" name="email" id="email"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        required>
                </div>

                <div class="flex justify-between items-center">
                    <a href="{{ route('siswa.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">
                        Kembali
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                        Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection