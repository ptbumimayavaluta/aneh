@extends('layouts.app')

@section('title', 'Kelola Pengguna - BMEX')
@section('page_heading', 'Kelola Akun Kasir & Admin')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-xl flex items-center gap-2">
            ✅ <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs p-4 rounded-xl flex items-center gap-2">
            ⚠️ <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm h-fit">
            <h2 class="font-bold text-sm text-gray-800 border-b pb-3 mb-4 flex items-center gap-2">
                ➕ Tambah Pengguna Baru
            </h2>

            <form action="{{ route('users.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Contoh: Wayan Kasir" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('name') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Email / Username</label>
                    <input type="email" name="email" required placeholder="kasir1@bmex.com" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('email') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required placeholder="******" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('password') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Peran (Role)</label>
                    <select name="role" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                        <option value="cashier">Cashier / Kasir</option>
                        <option value="admin">Administrator</option>
                    </select>
                    @error('role') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg transition shadow-sm">
                    Simpan Pengguna
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-xs uppercase tracking-wider">
                    Daftar Akun Terdaftar
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-700 uppercase font-bold tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Role</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 font-bold text-gray-800">{{ $user->name }}</td>
                            <td class="p-3 text-gray-600 font-mono">{{ $user->email }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                @if(Auth::id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-xs bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded-md transition">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">(Saya)</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection