<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::latest()->get();
        return view('currencies.index', compact('currencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'     => 'required|string|max:60|unique:currencies,code',
            'name'     => 'required|string|max:255',
            'symbol'   => 'nullable|string|max:10',
            'buy_rate' => 'required|numeric|min:0',
            'sell_rate'=> 'required|numeric|min:0',
        ]);

        $validated['symbol']    = $validated['symbol'] ?? '';
        $validated['is_active'] = $request->has('is_active');

        Currency::create($validated);

        return redirect()->route('currency.index')->with('success', 'Mata uang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);

        $validated = $request->validate([
            'code'     => 'required|string|max:60|unique:currencies,code,' . $currency->id,
            'name'     => 'required|string|max:255',
            'symbol'   => 'nullable|string|max:10',
            'buy_rate' => 'required|numeric|min:0',
            'sell_rate'=> 'required|numeric|min:0',
        ]);

        $validated['symbol']    = $validated['symbol'] ?? '';
        $validated['is_active'] = $request->has('is_active');

        $currency->update($validated);

        return redirect()->route('currency.index')->with('success', 'Data mata uang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->delete();

        return redirect()->route('currency.index')->with('success', 'Mata uang berhasil dihapus!');
    }
}