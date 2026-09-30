<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function create()
    {

        $currencies = Currency::where('is_active', true)->get();

        return view('transactions.create', compact('currencies'));
    }

    public function store(Request $request)
    {

    if ($request->has('items') && is_array($request->items)) {
        $items = $request->items;
        foreach ($items as $index => $item) {
            if (isset($item['rate'])) {
                $items[$index]['rate'] = str_replace(',', '.', $item['rate']);
            }
            if (isset($item['amount'])) {
                $items[$index]['amount'] = str_replace(',', '.', $item['amount']);
            }
        }
        $request->merge(['items' => $items]);
    }
        $validated = $request->validate([
            'customer_name'       => 'required|string|max:255',
            'id_type'             => 'required|in:KTP,PASSPORT,SIM,KITAS,Lainnya',
            'id_number'           => 'nullable|string|max:100',
            'country'             => 'required|string|max:100',
            'address'             => 'nullable|string',
            'type'                => 'required|in:BUY,SELL',
            'items'               => 'required|array|min:1',
            'items.*.currency_id' => 'required|exists:currencies,id',
            'items.*.amount'      => 'required|numeric|min:0.01',
            'items.*.rate'        => 'required|numeric|min:0.01',
        ]);

        $datePrefix = date('Ymd');
        $todayCount = Transaction::whereDate('created_at', now())->count() + 1;

        // Simpan setiap baris mata uang sebagai transaksi terpisah
        foreach ($validated['items'] as $item) {
            $totalIdr = $item['amount'] * $item['rate'];
            $trxCode  = 'TRX-' . $datePrefix . '-' . str_pad($todayCount++, 4, '0', STR_PAD_LEFT);

            Transaction::create([
                'trx_code'      => $trxCode,
                'customer_name' => $validated['customer_name'],
                'id_type'       => $validated['id_type'],
                'id_number'     => $validated['id_number'],
                'country'       => $validated['country'],
                'address'       => $validated['address'],
                'type'          => $validated['type'],
                'currency_id'   => $item['currency_id'],
                'amount'        => $item['amount'],
                'rate'          => $item['rate'],
                'total_idr'     => $totalIdr,
                'user_id'       => Auth::id(),
            ]);
        }

        return redirect()->route('transactions.create')->with('success', 'Berhasil menyimpan transaksi penukaran!');
    }
}