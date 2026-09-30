@extends('layouts.app')

@section('title', 'Currencies')
@section('page_heading', 'Currencies')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    
    <!-- Alert Success -->
    @if (session('success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 font-bold">&times;</button>
        </div>
    @endif

    <!-- Alert Error Validation -->
    @if ($errors->any())
        <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm">
            <p class="font-bold mb-1">Gagal menyimpan data:</p>
            <ul class="list-disc list-inside text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex justify-between items-center mb-5">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar Kurs Mata Uang</h2>
            <p class="text-xs text-gray-500">Data mata uang yang aktif untuk transaksi kasir.</p>
        </div>
        <button onclick="openCreateModal()" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow transition flex items-center gap-1.5">
            + Tambah Mata Uang
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs font-semibold uppercase tracking-wider border-b border-gray-200">
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Kode</th>
                    <th class="py-3 px-4">Nama Mata Uang</th>
                    <th class="py-3 px-4">Simbol</th>
                    <th class="py-3 px-4 text-right">Kurs Beli (IDR)</th>
                    <th class="py-3 px-4 text-right">Kurs Jual (IDR)</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($currencies as $index => $currency)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-medium text-gray-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $currency->code }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $currency->name }}</td>
                        <td class="py-3 px-4 font-semibold text-emerald-600">{{ $currency->symbol ?? '-' }}</td>
                        <td class="py-3 px-4 text-right font-mono font-medium text-gray-700">
                            Rp {{ number_format($currency->buy_rate, 2, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-medium text-gray-700">
                            Rp {{ number_format($currency->sell_rate, 2, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($currency->is_active)
                                <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-semibold">Aktif</span>
                            @else
                                <span class="bg-rose-100 text-rose-700 text-xs px-2.5 py-1 rounded-full font-semibold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ json_encode($currency) }})" class="text-slate-600 hover:text-slate-900 bg-gray-100 hover:bg-gray-200 px-2.5 py-1 rounded text-xs font-semibold transition">
                                    Edit
                                </button>
                                
                                <form action="{{ route('currency.destroy', $currency->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata uang {{ $currency->code }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded text-xs font-semibold transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-6 text-center text-gray-400">Belum ada data mata uang. Silakan tambah data baru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="currencyModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
        
        <div class="px-6 py-4 bg-slate-900 text-white flex justify-between items-center">
            <h3 id="modalTitle" class="text-sm font-bold uppercase tracking-wider">Tambah Mata Uang</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white font-bold">&times;</button>
        </div>

        <form id="currencyForm" method="POST" action="" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="methodInput" name="_method" value="POST">

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Mata Uang (USD, AUD, JPY) <span class="text-rose-500">*</span></label>
                <input type="text" id="code" name="code" required placeholder="Contoh: USD" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-slate-800 uppercase">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Mata Uang <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" required placeholder="Contoh: US Dollar" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-slate-800">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Simbol</label>
                <input type="text" id="symbol" name="symbol" placeholder="Contoh: $" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-slate-800">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kurs Beli (IDR) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" id="buy_rate" name="buy_rate" required placeholder="15000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-slate-800 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kurs Jual (IDR) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" id="sell_rate" name="sell_rate" required placeholder="15500" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-slate-800 font-mono">
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" class="rounded border-gray-300 text-slate-900 focus:ring-slate-800 cursor-pointer">
                <label for="is_active" class="text-xs font-semibold text-gray-700 cursor-pointer">Status Aktif untuk Transaksi</label>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg text-xs transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg text-xs shadow transition">Simpan</button>
            </div>
        </form>

    </div>
</div>

<script>
    const modal = document.getElementById('currencyModal');
    const form = document.getElementById('currencyForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodInput = document.getElementById('methodInput');

    function openCreateModal() {
        modalTitle.innerText = "TAMBAH MATA UANG";
        form.action = "{{ route('currency.store') }}";
        methodInput.value = "POST";

        document.getElementById('code').value = '';
        document.getElementById('name').value = '';
        document.getElementById('symbol').value = '';
        document.getElementById('buy_rate').value = '';
        document.getElementById('sell_rate').value = '';
        document.getElementById('is_active').checked = true;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openEditModal(currency) {
        modalTitle.innerText = "EDIT MATA UANG - " + currency.code;
        form.action = "/currency/" + currency.id;
        methodInput.value = "PUT";

        document.getElementById('code').value = currency.code;
        document.getElementById('name').value = currency.name;
        document.getElementById('symbol').value = currency.symbol || '';
        document.getElementById('buy_rate').value = currency.buy_rate;
        document.getElementById('sell_rate').value = currency.sell_rate;
        document.getElementById('is_active').checked = currency.is_active == 1;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection