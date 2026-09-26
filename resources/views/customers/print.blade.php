<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi - {{ $transaction->trx_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; padding: 0 !important; color: #000 !important; }
            .receipt-card { 
                border: none !important; 
                box-shadow: none !important; 
                padding: 0 !important; 
                width: 100% !important; 
                max-width: 100% !important; 
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-6 font-sans text-xs text-black" onload="window.print()">

    <div class="no-print mb-4 text-center">
        <button onclick="window.print()" class="bg-black hover:bg-gray-800 text-white px-5 py-2 rounded-lg shadow text-xs font-normal transition">
            🖨️ Cetak Nota Transaksi
        </button>
    </div>

    <div class="receipt-card max-w-sm mx-auto bg-white p-6 rounded-xl border border-black shadow-sm text-black">
        
        <div class="text-center border-b border-dashed border-black pb-3 mb-3">
            <h1 class="text-base uppercase tracking-wider text-black">BALI MONEY EXCHANGE</h1>
            <p class="text-[13px] text-black uppercase">KANTOR PUSAT BMEX</p>
            <a class="text-[10px] text-black uppercase">RICE TERRACE, JL. RAYA TEGALLALANG </a>
        </div>

        <div class="flex justify-between items-center text-[11px] border-b border-dashed border-black pb-2 mb-3 font-mono text-black">
            <span>No: {{ $transaction->trx_code }}</span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>

        <div class="space-y-1.5 text-xs border-b border-dashed border-black pb-3 mb-3 text-black">
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
            <div class="flex justify-between items-center pt-1">
                <span>Jenis Transaksi:</span>
                <span class="text-[10px] uppercase border border-black px-1.5 py-0.5 rounded text-black">
                    {{ strtolower($transaction->type) == 'buy' ? 'PEMBELIAN (BUY)' : 'PENJUALAN (SELL)' }}
                </span>
            </div>
        </div>

        <table class="w-full text-xs text-left mb-3 text-black">
            <thead>
                <tr class="border-b border-dashed border-black text-black uppercase">
                    <th class="py-1.5 text-left font-normal">CURR</th>
                    <th class="py-1.5 text-right font-normal">AMOUNT</th>
                    <th class="py-1.5 text-right font-normal">RATE</th>
                    <th class="py-1.5 text-right font-normal">TOTAL (IDR)</th>
                </tr>
            </thead>
            <tbody class="font-mono text-black">
                <tr class="border-b border-dashed border-black">
                    <td class="py-2 font-sans text-black">{{ $transaction->currency->code ?? '-' }}</td>
                    <td class="py-2 text-right text-black">{{ number_format($transaction->amount, 2, ',', '.') }}</td>
                    <td class="py-2 text-right text-black">{{ number_format($transaction->rate, 0, ',', '.') }}</td>
                    <td class="py-2 text-right text-black">{{ number_format($transaction->total_idr, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="border-b border-dashed border-black pb-3 mb-4 text-black">
            <div class="flex justify-between items-center text-sm">
                <span>TOTAL IDR</span>
                <span class="font-mono text-base text-black">Rp {{ number_format($transaction->total_idr, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-center text-[11px] my-6 text-black">
            <div>
                <p class="text-black">Customer</p>
                <div class="border-b border-black mt-12 mb-1 w-3/4 mx-auto"></div>
                <p class="text-black text-[10px] truncate">{{ $transaction->customer_name }}</p>
            </div>
            <div>
                <p class="text-black">Customer Service</p>
                <div class="border-b border-black mt-12 mb-1 w-3/4 mx-auto"></div>
                <p class="text-black text-[10px] truncate">{{ $transaction->user->name ?? 'Kasir' }}</p>
            </div>
        </div>

        <div class="text-center text-[9px] text-black space-y-1.5 italic border-t border-dashed border-black pt-3">
            <p>"This transaction was conducted below the threshold / equivalent to USD 10,000"</p>
            <p>"Please recount your money before leaving the outlet. No claims or complaints regarding a shortfall will be entertained after leaving the counter."</p>
        </div>

    </div>

</body>
</html>