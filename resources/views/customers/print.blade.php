<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi - {{ $transaction->trx_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Mengatur ukuran kertas printer thermal secara presisi agar tidak boros rol */
        @page {
            size: 80mm auto; /* Ubah ke 58mm jika menggunakan printer gulung kecil 58mm */
            margin: 2mm;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; padding: 0 !important; color: #000 !important; margin: 0 !important; }
            .receipt-card { 
                border: none !important; 
                box-shadow: none !important; 
                padding: 2px !important; 
                width: 100% !important; 
                max-width: 100% !important; 
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-4 font-sans text-xs text-black" onload="window.print()">

    <div class="no-print mb-4 text-center">
        <button onclick="window.print()" class="bg-black hover:bg-gray-800 text-white px-5 py-2 rounded-lg shadow text-xs font-normal transition">
            🖨️ Cetak Nota Transaksi
        </button>
    </div>

    <div class="receipt-card max-w-sm mx-auto bg-white p-4 rounded-xl border border-black shadow-sm text-black">
        
        <!-- Header -->
        <div class="text-center border-b border-dashed border-black pb-2 mb-2">
            <h1 class="text-sm uppercase tracking-wider font-bold text-black">BALI MONEY EXCHANGE</h1>
            <p class="text-[11px] text-black uppercase">KANTOR PUSAT BMEX</p>
            <p class="text-[9px] text-black uppercase">RICE TERRACE, JL. RAYA TEGALLALANG</p>
        </div>

        <!-- Kode & Tanggal -->
        <div class="flex justify-between items-center text-[10px] border-b border-dashed border-black pb-1.5 mb-2 font-mono text-black">
            <span>No: {{ $transaction->trx_code }}</span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>

        <!-- Detail Nasabah -->
        <div class="space-y-1 text-[11px] border-b border-dashed border-black pb-2 mb-2 text-black">
            <div class="flex justify-between">
                <span>Nasabah:</span>
                <span>{{ \Illuminate\Support\Str::limit($transaction->customer_name, 25) }}</span>
            </div>
            <div class="flex justify-between">
                <span>No. Identitas:</span>
                <span class="font-mono">{{ $transaction->id_type }} {{ $transaction->id_number ? '('.$transaction->id_number.')' : '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span>Negara:</span>
                <span>{{ $transaction->country ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center pt-0.5">
                <span>Jenis Transaksi:</span>
                <span class="text-[9px] uppercase border border-black px-1 py-0.5 rounded text-black">
                    {{ strtolower($transaction->type) == 'buy' ? 'PEMBELIAN (BUY)' : 'PENJUALAN (SELL)' }}
                </span>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <table class="w-full text-[11px] text-left mb-2 text-black">
            <thead>
                <tr class="border-b border-dashed border-black text-black uppercase text-[10px]">
                    <th class="py-1 text-left font-normal">CURR</th>
                    <th class="py-1 text-right font-normal">AMOUNT</th>
                    <th class="py-1 text-right font-normal">RATE</th>
                    <th class="py-1 text-right font-normal">TOTAL (IDR)</th>
                </tr>
            </thead>
            <tbody class="font-mono text-black">
                <tr class="border-b border-dashed border-black">
                    <td class="py-1 font-sans text-black">{{ $transaction->currency->code ?? '-' }}</td>
                    <td class="py-1 text-right text-black">{{ number_format($transaction->amount, 2, ',', '.') }}</td>
                    <td class="py-1 text-right text-black">{{ number_format($transaction->rate, 0, ',', '.') }}</td>
                    <td class="py-1 text-right text-black">{{ number_format($transaction->total_idr, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Total IDR -->
        <div class="border-b border-dashed border-black pb-2 mb-2 text-black">
            <div class="flex justify-between items-center text-xs">
                <span class="font-bold">TOTAL IDR</span>
                <span class="font-mono text-sm font-bold text-black">Rp {{ number_format($transaction->total_idr, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="grid grid-cols-2 gap-2 text-center text-[10px] my-3 text-black">
            <div>
                <p class="text-black">Customer</p>
                <div class="border-b border-black mt-7 mb-1 w-4/5 mx-auto"></div>
                <p class="text-black text-[9px] truncate">{{ $transaction->customer_name }}</p>
            </div>
            <div>
                <p class="text-black">Customer Service</p>
                <div class="border-b border-black mt-7 mb-1 w-4/5 mx-auto"></div>
                <p class="text-black text-[9px] truncate">{{ $transaction->user->name ?? 'Kasir' }}</p>
            </div>
        </div>

        <!-- Footer / Catatan -->
        <div class="text-center text-[8px] text-black space-y-1 italic border-t border-dashed border-black pt-2">
            <p>"This transaction was conducted below the threshold / equivalent to USD 10,000"</p>
            <p>"Please recount your money before leaving the outlet. No claims or complaints regarding a shortfall will be entertained after leaving the counter."</p>
        </div>

    </div>

</body>
</html>