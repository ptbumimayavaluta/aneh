@extends('layouts.app')

@section('title', 'Data Nasabah')
@section('page_heading', 'Data Nasabah')

@section('content')
<div class="space-y-8">

    <!-- Flash Alert Success -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-sm">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 font-bold">&times;</button>
        </div>
    @endif
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-800">Data Nasabah Aktif</h2>
                <p class="text-xs text-gray-500">Seluruh riwayat transaksi nasabah yang terdaftar di sistem.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs font-semibold uppercase tracking-wider border-b border-gray-200">
                        <th class="py-3 px-4">No TRX</th>
                        <th class="py-3 px-4">Nama Nasabah</th>
                        <th class="py-3 px-4">Identitas</th>
                        <th class="py-3 px-4">Negara</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4">Valas & Amount</th>
                        <th class="py-3 px-4 text-right">Total (IDR)</th>
                        <th class="py-3 px-4">Diinput / Diedit Oleh</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($activeNasabah as $data)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $data->trx_code }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-800">{{ $data->customer_name }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $data->id_type }} - {{ $data->id_number ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $data->country }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $data->type == 'BUY' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $data->type }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-700">
                                {{ $data->currency->code }} {{ number_format($data->amount, 2) }}
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-emerald-600">
                                Rp {{ number_format($data->total_idr, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-gray-500">
                                <div>Input: <span class="font-semibold text-gray-700">{{ $data->user->name }}</span></div>
                                @if($data->updatedBy)
                                    <div class="text-[10px] text-amber-600">Edit: {{ $data->updatedBy->name }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('customers.print', $data->id) }}" target="_blank" title="Cetak Nota"
                                       class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                    <a href="{{ route('customers.edit', $data->id) }}" title="Edit Data"
                                       class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-md transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('customers.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Gantungkan/hapus data nasabah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Gantungkan / Hapus Data" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-md transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-gray-400">Belum ada data nasabah aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-rose-50/50 rounded-xl shadow-sm border border-rose-200 p-6">
        <div class="mb-4">
            <h2 class="text-base font-bold text-rose-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Riwayat Hapus (Data Menggantung)
            </h2>
            <p class="text-xs text-rose-600">Data nasabah yang dihapus secara temporer beserta informasi eksekutornya.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-rose-100/60 text-rose-800 text-xs font-semibold uppercase tracking-wider border-b border-rose-200">
                        <th class="py-3 px-4">No TRX</th>
                        <th class="py-3 px-4">Nama Nasabah</th>
                        <th class="py-3 px-4">Valas</th>
                        <th class="py-3 px-4 text-right">Total IDR</th>
                        <th class="py-3 px-4">Diinput Oleh</th>
                        <th class="py-3 px-4">Dihapus (Menggantung) Oleh</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-100 text-xs">
                    @forelse($trashedNasabah as $trashed)
                        <tr class="hover:bg-rose-100/30 transition text-gray-600">
                            <td class="py-3 px-4 font-mono line-through font-bold text-rose-800">{{ $trashed->trx_code }}</td>
                            <td class="py-3 px-4 font-semibold">{{ $trashed->customer_name }}</td>
                            <td class="py-3 px-4">{{ $trashed->currency->code }} {{ number_format($trashed->amount, 2) }}</td>
                            <td class="py-3 px-4 text-right font-mono font-bold">Rp {{ number_format($trashed->total_idr, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">{{ $trashed->user->name }}</td>
                            <td class="py-3 px-4 font-semibold text-rose-700">
                                {{ $trashed->deletedBy->name ?? 'Sistem' }} 
                                <span class="text-[10px] font-normal text-rose-500 block">{{ $trashed->deleted_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('customers.print', $trashed->id) }}" target="_blank" title="Cetak Nota Arsip"
                                   class="p-1.5 bg-white border border-rose-200 hover:bg-rose-100 text-rose-700 rounded-md inline-block transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-rose-400">Tidak ada riwayat data menggantung saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection