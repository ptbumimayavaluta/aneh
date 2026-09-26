<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Currency;
use Carbon\Carbon;

class MutationController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year  = $request->input('year', date('Y'));

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate   = $startDate->copy()->endOfMonth()->endOfDay();

        $startData   = $this->getAccumulatedStart($startDate->format('Y-m-d'));
        $startCash   = $startData['cash'];
        $startStocks = $startData['stocks'];

        $trxAll = Transaction::whereBetween('created_at', [$startDate, $endDate])->get();

        $realCashBuy = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'BUY')
            ->sum('total_idr');

        $realCashSell = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'SELL')
            ->sum('total_idr');

        $displayPembelian = $trxAll->where('type', 'BUY')->sum('total_idr');
        $displayPenjualan = $trxAll->where('type', 'SELL')->sum('total_idr');

        $endCash = $startCash + $realCashSell - $realCashBuy;

        $tempCurrencyReport = $this->calculateForexMutation($trxAll, $startStocks);

        $currencyReport = [];
        $valEndTotal    = 0;
        $valStartTotal  = 0;

        foreach ($tempCurrencyReport as $row) {
            $currencyReport[] = $row;
            $valStartTotal += $row['awal']['total'];
            $valEndTotal   += $row['akhir']['valuation'];
        }

        $brankas = [
            'start'       => $startCash,
            'in'          => $displayPembelian,
            'out'         => $displayPenjualan,
            'end'         => $endCash,
            'start_asset' => $startCash + $valStartTotal,
            'end_asset'   => $endCash + $valEndTotal,
            'end_valas'   => $valEndTotal,
        ];
        $valuation = ['start' => $valStartTotal, 'end' => $valEndTotal];

        $filterMonth = $month;
        $filterYear  = $year;

        return view('mutations.index', compact(
            'month', 'year', 'filterMonth', 'filterYear',
            'brankas', 'valuation', 'currencyReport'
        ));
    }

    private function calculateForexMutation($transactions, $startStocks)
    {
        $currencies = Currency::where('is_active', 1)->orderBy('id', 'asc')->get();
        $report = [];

        foreach ($currencies as $curr) {
            $code = $curr->code;
            $stockData = $startStocks[$code] ?? ['qty' => 0, 'rate' => 0];
            
            $qtyAwal   = $stockData['qty'];
            $rateModal = $stockData['rate'];
            $valStartItem = $qtyAwal * $rateModal;

            $trxCurr = $transactions->where('currency_id', $curr->id)->sortBy('created_at');
            $beliQtyTotal = 0; $beliIdrTotal = 0; $jualQtyTotal = 0; $jualIdrTotal = 0;
            
            $runningQty  = $qtyAwal; 
            $runningRate = $rateModal;

            foreach ($trxCurr as $trx) {
                if ($trx->type === 'BUY') {
                    $oldValuation = $runningQty * $runningRate;
                    $newBuyVal    = $trx->total_idr;
                    $totalQty     = $runningQty + $trx->amount;
                    
                    if ($totalQty > 0) {
                        $runningRate = ($oldValuation + $newBuyVal) / $totalQty;
                    } else {
                        $runningRate = 0;
                    }
                    
                    $runningQty   += $trx->amount;
                    $beliQtyTotal += $trx->amount;
                    $beliIdrTotal += $trx->total_idr;
                } elseif ($trx->type === 'SELL') {
                    $runningQty   -= $trx->amount;
                    $jualQtyTotal += $trx->amount;
                    $jualIdrTotal += $trx->total_idr;
                }
            }

            $qtyAkhir     = $runningQty;
            $avgRateAkhir = $runningRate;
            $valEndItem   = $qtyAkhir * $avgRateAkhir;
            $profit       = ($valEndItem + $jualIdrTotal) - ($valStartItem + $beliIdrTotal);

            $report[] = [
                'currency'     => $code,
                'awal'         => ['qty' => $qtyAwal, 'rate' => $rateModal, 'total' => $valStartItem],
                'beli'         => ['qty' => $beliQtyTotal, 'total' => $beliIdrTotal],
                'jual'         => ['qty' => $jualQtyTotal, 'total' => $jualIdrTotal],
                'akhir'        => ['qty' => $qtyAkhir, 'avgRate' => $avgRateAkhir, 'valuation' => $valEndItem],
                'profit_gross' => $profit
            ];
        }
        return $report;
    }

    private function getAccumulatedStart($targetDate)
    {
        $allCurrencies = Currency::where('is_active', 1)->get();

        $cashIn = Transaction::where('created_at', '<', $targetDate . ' 00:00:00')
            ->where('type', 'SELL')
            ->sum('total_idr');

        $cashOut = Transaction::where('created_at', '<', $targetDate . ' 00:00:00')
            ->where('type', 'BUY')
            ->sum('total_idr');

        $totalCash = $cashIn - $cashOut;
        $finalStocks = [];

        foreach ($allCurrencies as $curr) {
            $code = $curr->code;
            $qty = 0;
            $avgRate = 0;

            $trxGap = Transaction::where('currency_id', $curr->id)
                ->where('created_at', '<', $targetDate . ' 00:00:00')
                ->orderBy('created_at')
                ->get();

            foreach ($trxGap as $t) {
                if ($t->type == 'BUY') {
                    $valBefore = $qty * $avgRate;
                    $valNew    = $t->total_idr;
                    $qty      += $t->amount;
                    
                    if ($qty > 0) {
                        $avgRate = ($valBefore + $valNew) / $qty;
                    } else {
                        $avgRate = 0;
                    }
                } else {
                    $qty -= $t->amount;
                }
            }

            $finalStocks[$code] = ['qty' => $qty, 'rate' => $avgRate];
        }

        return ['cash' => $totalCash, 'stocks' => $finalStocks];
    }
}