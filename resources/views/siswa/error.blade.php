@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 text-center">
        <div class="bg-red-50 border border-red-200 rounded-lg p-8 max-w-md mx-auto">
            <div class="text-red-500 text-5xl mb-4">❌</div>
            <h1 class="text-2xl font-bold text-red-800 mb-4">Data Siswa Tidak Ditemukan</h1>
            <p class="text-red-700 mb-6">{{ $message }}</p>
            <a href="{{ route('profile.edit') }}" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg">
                Periksa Profil
            </a>
        </div>
    </div>
@endsection