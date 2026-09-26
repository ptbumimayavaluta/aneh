<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            [
                'code'      => 'USD',
                'name'      => 'United States Dollar',
                'symbol'    => '$',
                'buy_rate'  => 15800.00,
                'sell_rate' => 16000.00,
                'is_active' => true,
            ],
            [
                'code'      => 'AUD',
                'name'      => 'Australian Dollar',
                'symbol'    => 'A$',
                'buy_rate'  => 10200.00,
                'sell_rate' => 10450.00,
                'is_active' => true,
            ],
            [
                'code'      => 'EUR',
                'name'      => 'Euro',
                'symbol'    => '€',
                'buy_rate'  => 17100.00,
                'sell_rate' => 17350.00,
                'is_active' => true,
            ],
            [
                'code'      => 'SGD',
                'name'      => 'Singapore Dollar',
                'symbol'    => 'S$',
                'buy_rate'  => 11800.00,
                'sell_rate' => 12000.00,
                'is_active' => true,
            ],
            [
                'code'      => 'JPY',
                'name'      => 'Japanese Yen',
                'symbol'    => '¥',
                'buy_rate'  => 102.50,
                'sell_rate' => 105.00,
                'is_active' => true,
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}