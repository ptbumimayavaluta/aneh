<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        $activeNasabah = Transaction::whereDate('created_at', $date)
                            ->with(['currency', 'user', 'updatedBy'])
                            ->latest()
                            ->get();

        $trashedNasabah = Transaction::onlyTrashed()
                            ->whereDate('created_at', $date)
                            ->with(['currency', 'user', 'deletedBy'])
                            ->latest('deleted_at')
                            ->get();

        return view('customers.index', compact('activeNasabah', 'trashedNasabah', 'date'));
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $currencies  = Currency::where('is_active', true)->get();

        return view('customers.edit', compact('transaction', 'currencies'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'id_type'       => 'required|in:KTP,PASSPORT,SIM,KITAS,Lainnya',
            'id_number'     => 'nullable|string|max:100',
            'country'       => 'required|string|max:100',
            'address'       => 'nullable|string',
            'type'          => 'required|in:BUY,SELL',
            'currency_id'   => 'required|exists:currencies,id',
            'amount'        => 'required|numeric|min:0.01',
            'rate'          => 'required|numeric|min:0.01',
        ]);

        $validated['total_idr']  = $validated['amount'] * $validated['rate'];
        $validated['updated_by'] = Auth::id();

        $transaction->update($validated);

        return redirect()->route('customers.index')->with('success', 'Data nasabah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->deleted_by = Auth::id();
        $transaction->save();

        $transaction->delete();

        return redirect()->route('customers.index')->with('success', 'Data nasabah ' . $transaction->trx_code . ' berhasil dipindahkan ke data menggantung!');
    }

    public function print($id)
    {
        // [BAGIAN UTAMA YANG DIUBAH]: Ambil data transaksi acuan berdasarkan ID
        $targetTransaction = Transaction::withTrashed()->findOrFail($id);

        // Ambil SEMUA baris transaksi yang memiliki trx_code yang sama persis
        $items = Transaction::withTrashed()
                    ->where('trx_code', $targetTransaction->trx_code)
                    ->with(['currency', 'user'])
                    ->get();

        return view('customers.print', [
            'transaction' => $targetTransaction,
            'items'       => $items
        ]);
    }
}