@extends('layouts.app')

@section('title', 'Edit Data Nasabah - Money Changer')
@section('page_heading', 'Edit Data Nasabah & Transaksi')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('customers.update', $transaction->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
            <h2 class="text-base font-bold text-slate-800">Kode Transaksi: {{ $transaction->trx_code }}</h2>
            <a href="{{ route('customers.index') }}" class="text-xs text-gray-500 hover:text-gray-800">&larr; Kembali</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="customer_name" value="{{ old('customer_name', $transaction->customer_name) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe ID</label>
                <select name="id_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    @foreach(['KTP', 'PASSPORT', 'SIM', 'KITAS', 'Lainnya'] as $type)
                        <option value="{{ $type }}" {{ $transaction->id_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor ID</label>
                <input type="text" name="id_number" value="{{ old('id_number', $transaction->id_number) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Negara</label>
                <input type="text" name="country" value="{{ old('country', $transaction->country) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Transaksi</label>
                <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-bold focus:outline-none focus:border-emerald-500">
                    <option value="BUY" {{ $transaction->type == 'BUY' ? 'selected' : '' }}>BUY</option>
                    <option value="SELL" {{ $transaction->type == 'SELL' ? 'selected' : '' }}>SELL</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat</label>
                <textarea name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500">{{ old('address', $transaction->address) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Mata Uang</label>
                <select name="currency_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    @foreach($currencies as $curr)
                        <option value="{{ $curr->id }}" {{ $transaction->currency_id == $curr->id ? 'selected' : '' }}>
                            {{ $curr->code }} - {{ $curr->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah Valas (Amount)</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:border-emerald-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Kurs (Rate IDR)</label>
                <input type="number" step="0.01" name="rate" value="{{ old('rate', $transaction->rate) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:border-emerald-500">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('customers.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Batal</a>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-6 py-2 rounded-lg text-sm shadow">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection