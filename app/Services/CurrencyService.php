<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Exception;

Class CurrencyService
{
    public function convert($amount, $fromCurrency, $toCurrency): float
    {
        $toUSD = ExchangeRate::where('base_currency','USD')
        ->where('target_currency',$fromCurrency)
        ->value('rate');

        $toWallet = ExchangeRate::where('base_currency','USD')
        ->where('target_currency',$toCurrency)
        ->value('rate');

        if(!$toWallet || !$toUSD){
            throw new Exception('Exchange rate not available');
        }

        $rate = $amount/$toUSD;
        $walletRate = round($toWallet*$rate,2);
        return $walletRate;
    }
}
