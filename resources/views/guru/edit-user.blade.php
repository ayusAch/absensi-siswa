@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Edit User Account Guru</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Edit akun login untuk guru {{ $guru->nama_lengkap }}</p>
                </div>
                <a href="{{ route('guru.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Informasi Guru -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informasi Guru</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama Lengkap</label>
                    <p class="text-gray-800 dark:text-white">{{ $guru->nama_lengkap }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">NIP</label>
                    <p class="text-gray-800 dark:text-white">{{ $guru->nip ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Email Guru</label>
                    <p class="text-gray-800 dark:text-white">{{ $guru->email }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                        {{ $guru->status == 'Aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ $guru->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Edit User -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informasi Login</h3>
            
            <form action="{{ route('guru.update-user', $guru) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Login <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 @error('email') border-red-500 @enderror"
                               value="{{ old('email', $guru->user->email) }}" 
                               placeholder="masukkan.email@sekolah.id"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Email ini akan digunakan untuk login ke sistem</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password Baru
                        </label>
                        <input type="password" name="password" id="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 @error('password') border-red-500 @enderror"
                               placeholder="Kosongkan jika tidak ingin mengubah">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Minimal 8 karakter</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500"
                               placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>
                </div>

                <!-- Informasi User -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-blue-800 dark:text-blue-300">
                            <p class="font-medium">Informasi User:</p>
                            <ul class="mt-2 space-y-1">
                                <li>• Role: <strong>Guru</strong></li>
                                <li>• Akun dibuat: {{ $guru->user->created_at->format('d/m/Y H:i') }}</li>
                                <li>• Terakhir update: {{ $guru->user->updated_at->format('d/m/Y H:i') }}</li>
                            </ul>