@extends('layouts.app')

@section('title', 'Input Biaya Operasional')
@section('page_heading', 'Biaya Operasional')

@section('content')
<div class="space-y-6">

    <!-- Notifikasi Sukses / Error -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-xs font-medium flex items-center justify-between shadow-sm">
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 font-bold">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-lg text-xs shadow-sm">
            <span class="font-bold block mb-1">Terjadi kesalahan input:</span>
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Input Biaya -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 h-fit">
            <h2 class="font-bold text-sm text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Biaya Operasional
            </h2>

            <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg text-xs font-medium text-gray-700 p-2.5 focus:ring-emerald-500 focus:border-emerald-500" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" placeholder="Contoh: 50000" class="w-full border border-gray-300 rounded-lg text-xs font-medium text-gray-700 p-2.5 focus:ring-emerald-500 focus:border-emerald-500 font-mono" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Keterangan / Keperluan</label>
                    <textarea name="description" rows="3" placeholder="Contoh: Pembelian ATK, Listrik, Konsumsi..." class="w-full border border-gray-300 rounded-lg text-xs font-medium text-gray-700 p-2.5 focus:ring-emerald-500 focus:border-emerald-500" required>{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 px-4 rounded-lg transition shadow-sm">
                    Simpan Biaya
                </button>
            </form>
        </div>

        <!-- Daftar & Filter Biaya -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h3 class="font-bold text-sm text-gray-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Daftar Biaya Operasional
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5 uppercase font-semibold tracking-wider">Periode: {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
                    </div>

                    <form action="{{ route('expenses.index') }}" method="GET" class="flex items-center gap-2">
                        <select name="month" class="border border-gray-300 rounded-lg text-xs font-bold text-gray-700 bg-white h-9 px-3 cursor-pointer focus:ring-emerald-500 focus:border-emerald-500" onchange="this.form.submit()">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m, 1)) }}</option>
                            @endforeach
                        </select>
                        <select name="year" class="border border-gray-300 rounded-lg text-xs font-bold text-gray-700 bg-white h-9 px-3 cursor-pointer focus:ring-emerald-500 focus:border-emerald-500" onchange="this.form.submit()">
                            @foreach(range(date('Y')-2, date('Y')+2) as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <!-- Ringkasan Total Biaya -->
                <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-3.5 mb-4 flex justify-between items-center">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Total Biaya Operasional Bulan Ini:</span>
                    <span class="text-sm font-black font-mono text-emerald-900">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead class="bg-gray-50 text-slate-700 border-b border-gray-200 uppercase font-bold tracking-wider">
                            <tr>
                                <th class="p-2.5">Tanggal</th>
                                <th class="p-2.5">Keterangan</th>
                                <th class="p-2.5 text-right">Nominal</th>
                                <th class="p-2.5 text-center">Kasir</th>
                                <th class="p-2.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($expenses as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-2.5 font-mono text-gray-600">{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                <td class="p-2.5 font-medium text-gray-800">{{ $item->description }}</td>
                                <td class="p-2.5 font-mono font-bold text-rose-600 text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                <td class="p-2.5 text-center text-gray-500">{{ $item->user->name ?? '-' }}</td>
                                <td class="p-2.5 text-center">
                                    <form action="{{ route('expenses.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus biaya ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold p-1 transition" title="Hapus">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-400 italic">Belum ada data biaya operasional pada periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection