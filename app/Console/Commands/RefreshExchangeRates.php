<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshExchangeRates extends Command
{
    protected $signature = 'rates:refresh'; // the artisan command that I will run in terminal
    protected $description = 'Fetch latest exchange rates from API and update the database';

    public function handle()
    {
        $apiKey = config('services.exchange_rate.key');
        $base = 'USD';
        $response = Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD");

        if(!$response->ok()){
            Log::error('Faild to fetch exchange rates');
            $this->error('Faild to fetch exchange rates');
            return;
        }

        $rates = $response->json('conversion_rates');
        foreach($rates as $targetCurrency => $rate){
            ExchangeRate::updateOrCreate(
                ['base_currency' => $base, 'target_currency' => $targetCurrency],
                ['rate' => $rate]
            );
        }
        Log::info('Exchange rates refreshed successfully.');
        $this->info('Exchange rates refreshed successfully.');
    }
}
