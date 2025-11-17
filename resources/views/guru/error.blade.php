@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 text-center">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 max-w-md mx-auto">
            <div class="text-yellow-500 text-5xl mb-4">⚠️</div>
            <h1 class="text-2xl font-bold text-yellow-800 mb-4">Data Guru Tidak Ditemukan</h1>
            <p class="text-yellow-700 mb-6">{{ $message }}</p>
            <a href="{{ route('profile.edit') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg">
                Periksa Profil
            </a>
        </div>
    </div>
@endsection