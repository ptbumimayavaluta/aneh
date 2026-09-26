@extends('layouts.app')

@section('title', 'From Transaksi')
@section('page_heading', 'Form Transaksi')

@section('content')
<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-control {
        border-radius: 0.375rem !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.75rem !important;
        border-color: #d1d5db !important;
        box-shadow: none !important;
        font-weight: 600 !important;
        color: #1e293b !important;
    }
    .ts-wrapper.single .ts-control:after {
        right: 12px !important;
    }
    .ts-dropdown {
        font-size: 0.75rem !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        border: 1px solid #e2e8f0 !important;
        z-index: 9999 !important;
    }
    .ts-dropdown .option {
        padding: 6px 12px !important;
    }
    /* MENGHILANGKAN NAVY: Diganti dengan warna terang (Slate 100) */
    .ts-dropdown .option.active {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
    }
</style>

<div class="max-w-5xl mx-auto">

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-sm">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 font-bold">&times;</button>
        </div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <h2 class="text-base font-bold text-slate-800 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                <span class="bg-slate-900 text-white text-xs font-bold px-2 py-0.5 rounded">STEP 1</span>
                DATA IDENTITAS NASABAH
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required placeholder="Masukkan nama sesuai identitas"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-slate-800">
                    @error('customer_name') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe ID <span class="text-rose-500">*</span></label>
                    <select name="id_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-slate-800">
                        <option value="KTP" {{ old('id_type') == 'KTP' ? 'selected' : '' }}>KTP</option>
                        <option value="PASSPORT" {{ old('id_type') == 'PASSPORT' ? 'selected' : '' }}>PASSPORT</option>
                        <option value="SIM" {{ old('id_type') == 'SIM' ? 'selected' : '' }}>SIM</option>
                        <option value="KITAS" {{ old('id_type') == 'KITAS' ? 'selected' : '' }}>KITAS</option>
                        <option value="Lainnya" {{ old('id_type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor Identitas (ID)</label>
                    <input type="text" name="id_number" value="{{ old('id_number') }}" placeholder="Identity Number"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Negara <span class="text-rose-500">*</span></label>
                    <input type="text" name="country" value="{{ old('country', '') }}" required placeholder="Country"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-slate-800">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat</label>
                    <textarea name="address" rows="2" placeholder="Alamat lengkap nasabah"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-slate-800">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-visible">
            <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-200 flex justify-between items-center rounded-t-xl">
                <div class="flex items-center gap-2">
                    <span class="bg-[#0b132a] text-white text-[10px] font-extrabold px-2 py-1 rounded">STEP 2</span>
                    <h2 class="text-sm font-bold text-slate-800 tracking-wide uppercase">RINCIAN MATA UANG</h2>
                </div>
                <button type="button" id="btn-add-row" class="bg-[#0b132a] hover:bg-slate-800 text-white text-xs font-bold px-3.5 py-1.5 rounded transition shadow-sm flex items-center gap-1">
                    + TAMBAH BARIS
                </button>
            </div>

            <div class="bg-[#fffdf0] border-b border-amber-100 p-4 flex items-center gap-4">
                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">JENIS TRANSAKSI</label>
                <select name="type" id="trx_type" required class="border border-gray-300 rounded px-3 py-1.5 text-xs font-black text-slate-800 bg-white focus:outline-none focus:border-slate-800 cursor-pointer shadow-sm">
                    <option value="BUY" {{ old('type') == 'BUY' ? 'selected' : '' }}>BELI</option>
                    <option value="SELL" {{ old('type') == 'SELL' ? 'selected' : '' }}>JUAL</option>
                </select>
            </div>

            <div class="w-full">
                <table class="w-full text-xs text-left border-collapse" id="items-table">
                    <thead>
                        <tr class="bg-[#0b132a] text-white font-bold uppercase tracking-wider">
                            <th class="p-3 w-3/12">CURRENCY</th>
                            <th class="p-3 w-3/12 text-right">AMOUNT</th>
                            <th class="p-3 w-2/12 text-right">RATE</th>
                            <th class="p-3 w-3/12 text-right">TOTAL</th>
                            <th class="p-3 w-12 text-center">HAPUS</th>
                        </tr>
                    </thead>
                    <tbody id="items-container" class="divide-y divide-gray-100">
                        <tr class="item-row hover:bg-slate-50/50">
                            <td class="p-3 align-middle">
                                <select name="items[0][currency_id]" class="currency-select w-full" required>
                                    <option value="" disabled selected>Pilih Currency...</option>
                                    @foreach ($currencies as $curr)
                                        <option value="{{ $curr->id }}" data-code="{{ $curr->code }}" data-buy="{{ $curr->buy_rate }}" data-sell="{{ $curr->sell_rate }}" {{ old('items.0.currency_id') == $curr->id ? 'selected' : '' }}>
                                            {{ $curr->code }} - {{$curr->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-3 align-middle">
                                <input type="text" inputmode="decimal" name="items[0][amount]" value="0" class="amount-input w-full border border-gray-300 rounded px-3 py-2 text-xs font-mono text-right focus:outline-none focus:border-slate-800" required>
                            </td>
                            <td class="p-3 align-middle">
                                <input type="text" inputmode="decimal" name="items[0][rate]" value="0" class="rate-input w-full border border-gray-300 rounded px-3 py-2 text-xs font-mono text-right focus:outline-none focus:border-slate-800" required>
                            </td>
                            <td class="p-3 align-middle">
                                <input type="text" readonly value="0" class="total-row-input w-full border border-gray-200 bg-gray-50 rounded px-3 py-2 text-xs font-mono font-bold text-right text-slate-800 focus:outline-none">
                            </td>
                            <td class="p-3 text-center align-middle">
                                <button type="button" class="btn-remove-row text-gray-400 hover:text-rose-600 p-1.5 transition rounded" title="Hapus Baris">
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center rounded-b-xl">
                <div class="w-full text-center">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">TOTAL TRANSAKSI (IDR)</span>
                </div>
                <div class="min-w-[150px] text-right">
                    <span class="text-base font-black text-slate-900 font-mono" id="grand-total-display">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-2.5 rounded-lg text-sm shadow transition">
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('items-container');
    const btnAdd = document.getElementById('btn-add-row');
    const trxTypeInput = document.getElementById('trx_type');
    const grandTotalDisplay = document.getElementById('grand-total-display');

    const currenciesData = @json($currencies);
    let rowIndex = 1;

    function parseNumberInput(val) {
        if (!val) return 0;
        const cleanVal = val.toString().replace(',', '.');
        return parseFloat(cleanVal) || 0;
    }

    function attachSearchableSelect(selectElement) {
        return new TomSelect(selectElement, {
            create: false,
            placeholder: "Ketik Kode / Nama...",
            dropdownParent: 'body',
            render: {
                option: function(data, escape) {
                    const parts = data.text.split(' - ');
                    return `<div class="py-1">
                                <div class="font-bold text-slate-800 text-xs">${escape(parts[0])}</div>
                                <div class="text-[11px] text-slate-500">${escape(parts[1] || '')}</div>
                            </div>`;
                },
                item: function(data, escape) {
                    return `<div>${escape(data.text.split(' - ')[0])}</div>`;
                }
            },
            onChange: function(value) {
                const row = selectElement.closest('.item-row');
                updateRowRate(row);

                const amountInput = row.querySelector('.amount-input');
                if (amountInput) {
                    amountInput.focus();
                    if (amountInput.value === '0') amountInput.select();
                }
            }
        });
    }

    const firstSelect = container.querySelector('.currency-select');
    if (firstSelect) {
        attachSearchableSelect(firstSelect);
    }

    function buildRowHtml(index) {
        let optionsHtml = '<option value="" disabled selected>Pilih Currency...</option>';
        currenciesData.forEach(curr => {
            optionsHtml += `<option value="${curr.id}" data-code="${curr.code}" data-buy="${curr.buy_rate}" data-sell="${curr.sell_rate}">
                ${curr.code} - ${curr.name}
            </option>`;
        });

        return `
        <tr class="item-row hover:bg-slate-50/50">
            <td class="p-3 align-middle">
                <select name="items[${index}][currency_id]" class="currency-select w-full" required>
                    ${optionsHtml}
                </select>
            </td>
            <td class="p-3 align-middle">
                <input type="text" inputmode="decimal" name="items[${index}][amount]" value="0" class="amount-input w-full border border-gray-300 rounded px-3 py-2 text-xs font-mono text-right focus:outline-none focus:border-slate-800" required>
            </td>
            <td class="p-3 align-middle">
                <input type="text" inputmode="decimal" name="items[${index}][rate]" value="0" class="rate-input w-full border border-gray-300 rounded px-3 py-2 text-xs font-mono text-right focus:outline-none focus:border-slate-800" required>
            </td>
            <td class="p-3 align-middle">
                <input type="text" readonly value="0" class="total-row-input w-full border border-gray-200 bg-gray-50 rounded px-3 py-2 text-xs font-mono font-bold text-right text-slate-800 focus:outline-none">
            </td>
            <td class="p-3 text-center align-middle">
                <button type="button" class="btn-remove-row text-gray-400 hover:text-rose-600 p-1.5 transition rounded" title="Hapus Baris">
                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </td>
        </tr>`;
    }

    btnAdd.addEventListener('click', function() {
        const rowHtml = buildRowHtml(rowIndex);
        container.insertAdjacentHTML('beforeend', rowHtml);

        const newRow = container.lastElementChild;
        const newSelect = newRow.querySelector('.currency-select');
        attachSearchableSelect(newSelect);

        rowIndex++;
        calculateGrandTotal();
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-row')) {
            const rows = container.querySelectorAll('.item-row');
            if (rows.length > 1) {
                e.target.closest('.item-row').remove();
                calculateGrandTotal();
            } else {
                alert('Transaksi minimal memiliki 1 baris mata uang.');
            }
        }
    });

    trxTypeInput.addEventListener('change', function() {
        const rows = container.querySelectorAll('.item-row');
        rows.forEach(row => updateRowRate(row));
    });

    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('amount-input') || e.target.classList.contains('rate-input')) {
            const row = e.target.closest('.item-row');
            calculateRowTotal(row);
        }
    });

    function updateRowRate(row) {
        const select = row.querySelector('.currency-select');
        if (!select || !select.value) return;

        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption) return;

        const buyRate = selectedOption.getAttribute('data-buy') || 0;
        const sellRate = selectedOption.getAttribute('data-sell') || 0;
        const rateInput = row.querySelector('.rate-input');

        if (trxTypeInput.value === 'BUY') {
            rateInput.value = buyRate;
        } else {
            rateInput.value = sellRate;
        }

        calculateRowTotal(row);
    }

    function calculateRowTotal(row) {
        const amount = parseNumberInput(row.querySelector('.amount-input').value);
        const rate = parseNumberInput(row.querySelector('.rate-input').value);
        const total = amount * rate;

        row.querySelector('.total-row-input').value = new Intl.NumberFormat('id-ID').format(total);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        const rows = container.querySelectorAll('.item-row');

        rows.forEach(row => {
            const amount = parseNumberInput(row.querySelector('.amount-input').value);
            const rate = parseNumberInput(row.querySelector('.rate-input').value);
            grandTotal += (amount * rate);
        });

        grandTotalDisplay.textContent = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(grandTotal);
    }
});
</script>
@endsection