<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // Halaman Utam Data Nasabah (Aktif & Menggantung)
    public function index()
    {
        // Data Nasabah Aktif
        $activeNasabah = Transaction::with(['currency', 'user', 'updatedBy'])
                            ->latest()
                            ->get();

        // Data Nasabah Menggantung (Soft Deleted)
        $trashedNasabah = Transaction::onlyTrashed()
                            ->with(['currency', 'user', 'deletedBy'])
                            ->latest('deleted_at')
                            ->get();

        return view('customers.index', compact('activeNasabah', 'trashedNasabah'));
    }

    // Form Edit Data Nasabah & Transaksi
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $currencies  = Currency::where('is_active', true)->get();

        return view('customers.edit', compact('transaction', 'currencies'));
    }

    // Update Data Nasabah
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
        $transaction = Transaction::withTrashed()->with(['currency', 'user'])->findOrFail($id);

        return view('customers.print', compact('transaction'));
    }
}