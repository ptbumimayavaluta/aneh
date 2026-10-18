HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi - {{ $transaction->trx_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: 76mm auto; /* Ukuran rol printer Epson TM-U220 */
            margin: 0mm;     /* Hilangkan margin kertas bawaan browser */
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
<body class="bg-gray-100 p-2 font-sans text-[10px] text-black font-normal" onload="window.print()">

    <div class="no-print mb-4 text-center">
        <button onclick="window.print()" class="bg-black hover:bg-gray-800 text-white px-5 py-2 rounded-lg shadow text-xs font-normal transition">
            🖨️ Cetak Nota Transaksi
        </button>
    </div>

    <div class="receipt-card max-w-sm mx-auto bg-white p-2 rounded border border-black shadow-sm text-black">
        
        <!-- Header -->
        <div class="text-center border-b border-dashed border-black pb-1 mb-1.5">
            <h1 class="text-[10px] uppercase text-black font-normal">BALI MONEY EXCHANGE</h1>
            <p class="text-[10px] text-black uppercase font-normal">KANTOR PUSAT BMEX</p>
            <p class="text-[10px] text-black uppercase font-normal">RICE TERRACE, JL. RAYA TEGALLALANG</p>
        </div>

        <!-- Kode & Tanggal -->
        <div class="flex justify-between items-center text-[10px] border-b border-dashed border-black pb-1 mb-1.5 font-mono text-black font-normal">
            <span>No: {{ $transaction->trx_code }}</span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>

        <!-- Detail Nasabah -->
        <div class="space-y-0.5 text-[10px] border-b border-dashed border-black pb-1 mb-1.5 text-black font-normal">
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
                <span class="text-[10px] uppercase border border-black px-1 text-black font-normal">
                    {{ strtolower($transaction->type) == 'buy' ? 'PEMBELIAN (BUY)' : 'PENJUALAN (SELL)' }}
                </span>
            </div>
        </div>

        <!-- Tabel Transaksi (Tanpa garis pemisah antar mata uang) -->
        <table class="w-full text-[10px] text-left mb-1.5 text-black font-normal">
            <thead>
                <tr class="border-b border-dashed border-black text-black uppercase text-[10px]">
                    <th class="py-0.5 text-left font-normal">CURR</th>
                    <th class="py-0.5 text-right font-normal">AMOUNT</th>
                    <th class="py-0.5 text-right font-normal">RATE</th>
                    <th class="py-0.5 text-right font-normal">TOTAL (IDR)</th>
                </tr>
            </thead>
            <tbody class="font-mono text-black">
                @foreach($items as $item)
                <tr>
                    <td class="py-0.5 font-sans text-black font-normal">{{ $item->currency->code ?? '-' }}</td>
                    <td class="py-0.5 text-right text-black font-normal">{{ number_format($item->amount, 2, ',', '.') }}</td>
                    <td class="py-0.5 text-right text-black font-normal">{{ number_format($item->rate, 0, ',', '.') }}</td>
                    <td class="py-0.5 text-right text-black font-normal">{{ number_format($item->total_idr, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="border-b border-dashed border-black pt-0.5"></td>
                </tr>
            </tfoot>
        </table>

        <!-- Total Keseluruhan IDR (Tanpa Bold) -->
        <div class="border-b border-dashed border-black pb-1 mb-1.5 text-black font-normal">
            <div class="flex justify-between items-center text-[10px]">
                <span>TOTAL IDR</span>
                <span class="font-mono text-[10px] text-black font-normal">Rp {{ number_format($items->sum('total_idr'), 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="grid grid-cols-2 gap-2 text-center text-[10px] my-2 text-black font-normal">
            <div>
                <p class="text-black font-normal">Customer</p>
                <div class="border-b border-black mt-5 mb-0.5 w-4/5 mx-auto"></div>
                <p class="text-black text-[10px] truncate font-normal">{{ $transaction->customer_name }}</p>
            </div>
            <div>
                <p class="text-black font-normal">Customer Service</p>
                <div class="border-b border-black mt-5 mb-0.5 w-4/5 mx-auto"></div>
                <p class="text-black text-[10px] truncate font-normal">{{ $transaction->user->name ?? 'Kasir' }}</p>
            </div>
        </div>

        <!-- Footer / Catatan -->
        <div class="text-center text-[10px] text-black space-y-0.5 italic border-t border-dashed border-black pt-1 font-normal">
            <p>"This transaction was conducted below the threshold / equivalent to USD 10,000"</p>
            <p>"Please recount your money before leaving the outlet. No claims or complaints regarding a shortfall will be entertained after leaving the counter."</p>
        </div>

    </div>

</body>
</html>