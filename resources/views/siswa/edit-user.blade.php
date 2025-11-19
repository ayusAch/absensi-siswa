@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-2xl">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Edit Akun User Siswa</h2>

            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h3 class="font-semibold text-blue-800 dark:text-blue-300">Informasi Siswa:</h3>
                <p class="text-blue-700 dark:text-blue-400">
                    <strong>NIS:</strong> {{ $siswa->nis }} |
                    <strong>Nama:</strong> {{ $siswa->nama }} |
                    <strong>Kelas:</strong> {{ $siswa->kelas->nama ?? '-' }}
                </p>
            </div>

            <form action="{{ route('siswa.update-user', $siswa) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address
                    </label>
                    <input type="email" name="email" id="email"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        value="{{ old('email', $siswa->user->email) }}" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password Baru (kosongkan jika tidak ingin mengubah)
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex justify-between items-center">
                    <a href="{{ route('siswa.user-list') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">
                        Kembali
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                        Update Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection