<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperationalExpense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OperationalExpenseController extends Controller
{
    // Menampilkan halaman dan daftar biaya operasional berdasarkan filter bulan & tahun
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year  = $request->input('year', date('Y'));

        $expenses = OperationalExpense::with('user')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalExpense = $expenses->sum('amount');

        return view('expenses.index', compact('expenses', 'totalExpense', 'month', 'year'));
    }

    // Menyimpan data biaya operasional baru
    public function store(Request $request)
    {
        $request->validate([
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ], [
            'date.required'        => 'Tanggal wajib diisi.',
            'amount.required'      => 'Nominal biaya wajib diisi.',
            'amount.numeric'       => 'Nominal biaya harus berupa angka.',
            'description.required' => 'Keterangan biaya wajib diisi.',
        ]);

        OperationalExpense::create([
            'date'        => $request->date,
            'amount'      => $request->amount,
            'description' => $request->description,
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('expenses.index', [
            'month' => date('m', strtotime($request->date)),
            'year'  => date('Y', strtotime($request->date))
        ])->with('success', 'Biaya operasional berhasil ditambahkan!');
    }

    // Menghapus data biaya operasional
    public function destroy($id)
    {
        $expense = OperationalExpense::findOrFail($id);
        $expense->delete();

        return redirect()->back()->with('success', 'Biaya operasional berhasil dihapus!');
    }
}