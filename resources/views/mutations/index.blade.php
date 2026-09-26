@extends('layouts.app')

@section('title', 'Mutasi Transaksi')
@section('page_heading', 'Mutasi Transaksi')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-base text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan Mutasi Transaksi
                </h2>
                <p class="text-xs text-gray-500 mt-0.5 uppercase font-semibold tracking-wider">
                    Periode: {{ date('F Y', mktime(0, 0, 0, $filterMonth, 1, $filterYear)) }}
                </p>
            </div>

            <form action="{{ route('mutations.index') }}" method="GET" class="flex items-center gap-2">
                <select name="month" class="border border-gray-300 rounded-lg text-xs font-bold text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 bg-white cursor-pointer h-9 px-3" onchange="this.form.submit()">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $filterMonth == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m, 1)) }}</option>
                    @endforeach
                </select>

                <select name="year" class="border border-gray-300 rounded-lg text-xs font-bold text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 bg-white cursor-pointer h-9 px-3" onchange="this.form.submit()">
                    @foreach(range(date('Y')-2, date('Y')+2) as $y)
                        <option value="{{ $y }}" {{ $filterYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @php
        $hpp = $valuation['start'] + $brankas['in'] - $valuation['end'];
        $laba = $brankas['out'] - $hpp;

        // Helper format angka standar Indonesia (Titik untuk ribuan)
        $fmt = function($val) {
            return number_format($val, 0, ',', '.');
        };

        // Helper format rate: Titik untuk ribuan, Koma jika ada pecahan desimal
        $formatRate = function($val) {
            if (!$val || $val == 0) return '-';
            return ($val == floor($val)) ? number_format($val, 0, ',', '.') : number_format($val, 2, ',', '.');
        };
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Penjualan</span>
            <span class="text-lg font-black text-gray-800 mt-1 block">Rp {{ $fmt($brankas['out']) }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Pembelian Valas</span>
            <span class="text-lg font-black text-emerald-600 mt-1 block">Rp {{ $fmt($brankas['in']) }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Estimasi Laba</span>
            <span class="text-lg font-black {{ $laba >= 0 ? 'text-emerald-700' : 'text-rose-600' }} mt-1 block">Rp {{ $fmt($laba) }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Aset (Kas + Valas)</span>
            <span class="text-lg font-black text-slate-900 mt-1 block">Rp {{ $fmt($brankas['end_asset']) }}</span>
        </div>
    </div>

    <div class="space-y-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-xs uppercase tracking-wider flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-emerald-600 rounded-sm"></span>
                    I. Arus Kas Fisik (Tunai)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-white text-slate-700 border-b border-gray-200 uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-3 py-3 text-center">Modal Awal</th>
                            <th class="px-3 py-3 text-center text-emerald-700">Beli (Out)</th>
                            <th class="px-3 py-3 text-center text-rose-700">Jual (In)</th>
                            <th class="px-3 py-3 text-center bg-slate-50 text-slate-900">Sisa Fisik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="text-center font-mono font-bold text-gray-800">
                            <td class="px-3 py-4">Rp {{ $fmt($brankas['start']) }}</td>
                            <td class="px-3 py-4 text-emerald-600">Rp {{ $fmt($brankas['in']) }}</td>
                            <td class="px-3 py-4 text-rose-600">Rp {{ $fmt($brankas['out']) }}</td>
                            <td class="px-3 py-4 bg-slate-50 text-slate-900 font-black">Rp {{ $fmt($brankas['end']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-xs uppercase tracking-wider flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-emerald-600 rounded-sm"></span>
                    II. Laporan Laba Rugi (Estimasi)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-2.5 font-bold text-gray-700">A. TOTAL PENJUALAN (OMSET)</td>
                            <td class="px-4 py-2.5 font-mono font-bold text-emerald-700 text-right">Rp {{ $fmt($brankas['out']) }}</td>
                        </tr>
                        <tr class="bg-gray-50/40">
                            <td class="px-4 py-2 text-gray-500 pl-8">1. Persediaan Awal Valas</td>
                            <td class="px-4 py-2 font-mono text-gray-600 text-right">{{ $fmt($valuation['start']) }}</td>
                        </tr>
                        <tr class="bg-gray-50/40">
                            <td class="px-4 py-2 text-gray-500 pl-8">2. Pembelian Valas (+)</td>
                            <td class="px-4 py-2 font-mono text-gray-600 text-right">{{ $fmt($brankas['in']) }}</td>
                        </tr>
                        <tr class="bg-gray-50/40">
                            <td class="px-4 py-2 text-gray-500 pl-8">3. Persediaan Akhir Valas (-)</td>
                            <td class="px-4 py-2 font-mono text-gray-600 text-right">({{ $fmt($valuation['end']) }})</td>
                        </tr>
                        <tr class="border-t border-gray-200">
                            <td class="px-4 py-2 font-bold text-gray-800">B. TOTAL HPP (1 + 2 - 3)</td>
                            <td class="px-4 py-2 font-mono font-bold text-rose-600 text-right">Rp ({{ $fmt($hpp) }})</td>
                        </tr>
                        <tr class="bg-slate-900 text-white">
                            <td class="px-4 py-2.5 font-black uppercase">C. ESTIMASI LABA (A - B)</td>
                            <td class="px-4 py-2.5 font-mono font-black text-sm text-right">Rp {{ $fmt($laba) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-xs uppercase tracking-wider flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-emerald-600 rounded-sm"></span>
                    III. Rincian Mutasi Per Mata Uang
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="p-2.5 text-center align-middle border-r border-gray-200 bg-slate-50 sticky left-0 z-10" rowspan="2">KODE</th>
                            <th class="p-2.5 text-center border-r border-gray-200" colspan="2">STOK AWAL</th>
                            <th class="p-2.5 text-center border-r border-gray-200" colspan="2">PEMBELIAN</th>
                            <th class="p-2.5 text-center border-r border-gray-200" colspan="2">PENJUALAN</th>
                            <th class="p-2.5 text-center" colspan="3">STOK AKHIR</th>
                        </tr>
                        <tr class="text-[10px] text-gray-500 border-t border-gray-200">
                            <th class="p-2 text-center">Qty</th>
                            <th class="p-2 text-center border-r border-gray-200">IDR</th>
                            <th class="p-2 text-center">Qty</th>
                            <th class="p-2 text-center border-r border-gray-200">IDR</th>
                            <th class="p-2 text-center">Qty</th>
                            <th class="p-2 text-center border-r border-gray-200">IDR</th>
                            <th class="p-2 text-center">Qty</th>
                            <th class="p-2 text-center">Rate</th>
                            <th class="p-2 text-center">Valuasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-mono">
                        @foreach($currencyReport as $row)
                        <tr class="hover:bg-slate-50/80 transition duration-150">
                            <td class="p-2.5 font-sans font-bold text-center text-slate-900 border-r border-gray-200 bg-white sticky left-0">
                                {{ $row['currency'] }}
                            </td>
                            <td class="p-2.5 text-right text-gray-700">{{ $row['awal']['qty'] > 0 ? $fmt($row['awal']['qty']) : '0' }}</td>
                            <td class="p-2.5 text-right text-gray-700 border-r border-gray-200">{{ $row['awal']['total'] > 0 ? $fmt($row['awal']['total']) : '0' }}</td>
                            
                            <td class="p-2.5 text-right font-semibold text-emerald-700 bg-emerald-50/20">{{ $row['beli']['qty'] > 0 ? $fmt($row['beli']['qty']) : '-' }}</td>
                            <td class="p-2.5 text-right text-emerald-700 bg-emerald-50/20 border-r border-gray-200">{{ $row['beli']['total'] > 0 ? $fmt($row['beli']['total']) : '-' }}</td>
                            
                            <td class="p-2.5 text-right font-semibold text-rose-700 bg-rose-50/20">{{ $row['jual']['qty'] > 0 ? $fmt($row['jual']['qty']) : '-' }}</td>
                            <td class="p-2.5 text-right text-rose-700 bg-rose-50/20 border-r border-gray-200">{{ $row['jual']['total'] > 0 ? $fmt($row['jual']['total']) : '-' }}</td>
                            
                            <td class="p-2.5 text-right font-bold text-slate-800 bg-slate-50/40">{{ $row['akhir']['qty'] > 0 ? $fmt($row['akhir']['qty']) : '0' }}</td>
                            <td class="p-2.5 text-right text-slate-700 bg-slate-50/40">{{ $formatRate($row['akhir']['avgRate']) }}</td>
                            <td class="p-2.5 text-right font-bold text-slate-900 bg-slate-50/40">{{ $row['akhir']['valuation'] > 0 ? $fmt($row['akhir']['valuation']) : '0' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-100 text-slate-900 text-xs font-bold border-t border-gray-200">
                       <tr>
                            <td colspan="9" class="p-3 text-right uppercase tracking-wider font-sans">Total Valuasi Aset Valas Akhir:</td>
                            <td class="p-3 text-right font-mono font-black text-slate-900">Rp {{ $fmt($valuation['end']) }}</td>
                       </tr>
                   </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection