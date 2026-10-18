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

    /**
     * [BARU] Endpoint AJAX untuk Pencarian Otomatis Nasabah Lama
     */
    public function searchCustomer(Request $request)
    {
        $keyword = $request->input('q');
        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $customers = Transaction::select('customer_name', 'id_type', 'id_number', 'country', 'address')
            ->where('customer_name', 'LIKE', "%{$keyword}%")
            ->orWhere('id_number', 'LIKE', "%{$keyword}%")
            ->orderBy('id', 'desc')
            ->get()
            ->unique('customer_name')
            ->values();

        return response()->json($customers);
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

        // Konversi otomatis semua input teks menjadi Huruf Kapital (Uppercase)
        $customerName = strtoupper($validated['customer_name']);
        $idNumber     = strtoupper($validated['id_number'] ?? '');
        $country      = strtoupper($validated['country']);
        $address      = strtoupper($validated['address'] ?? '');
        $trxType      = strtoupper($validated['type']);

        $datePrefix = date('Ymd');
        $todayCount = Transaction::whereDate('created_at', now())->count() + 1;

        // Generate 1 kode nota bersama untuk semua item dalam sekali submit
        $trxCode = 'TRX-' . $datePrefix . '-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

        // Simpan semua item mata uang dengan trx_code yang sama persis
        foreach ($validated['items'] as $item) {
            $totalIdr = $item['amount'] * $item['rate'];

            Transaction::create([
                'trx_code'      => $trxCode, // Kode nota sama untuk semua mata uang dalam 1 transaksi
                'customer_name' => $customerName,
                'id_type'       => $validated['id_type'],
                'id_number'     => $idNumber,
                'country'       => $country,
                'address'       => $address,
                'type'          => $trxType,
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