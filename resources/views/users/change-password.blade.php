@extends('layouts.app')

@section('title', 'Ganti Password - BMEX')
@section('page_heading', 'Ganti Password Akun')

@section('content')
<div class="max-w-md mx-auto space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-xl flex items-center gap-2">
            ✅ <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h2 class="font-bold text-sm text-gray-800 border-b pb-3 mb-4 flex items-center gap-2">
            🔐 Perbarui Password
        </h2>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required placeholder="Masukkan password lama" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                @error('current_password') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                @error('password') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password baru" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg transition shadow-sm">
                Perbarui Password
            </button>
        </form>
    </div>
</div>
@endsection