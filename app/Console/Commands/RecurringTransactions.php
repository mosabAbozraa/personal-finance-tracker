<?php

namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RecurringTransactions extends Command
{
    protected $signature = 'create:recurring-transactions';
    protected $description = 'Create recurring transactions';


    public function handle(){

        $recurring_transactions = RecurringTransaction::where('is_active',true)
        ->whereDate('next_run_at', '<=', now())
        ->with('wallet')
        ->get();

        foreach($recurring_transactions as $transaction){
            Transaction::create([
                // 'user_id'       => $transaction->user_id,
                'wallet_id'     => $transaction->wallet_id,
                'category_id'   => $transaction->category_id,
                'amount'        => $transaction->amount,
                'date'          => now(),
                'notes'         => $transaction->notes,
                'type'          => $transaction->type,
                'currency'      => $transaction->wallet->currency
            ]);

            $transaction->calculateNextRun();
        }

        Log::info('Recurring transactions created successfully.');
        $this->info('Recurring transactions created successfully.');
    }
}
