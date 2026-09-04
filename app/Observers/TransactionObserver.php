<?php

namespace App\Observers;

use App\Events\BudgetExceeded;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        if($transaction->type === 'income'){
            $transaction->wallet->increment('balance', $transaction->amount);
        }
        else{
            $transaction->wallet->decrement('balance', $transaction->amount);
        }

        if ($transaction->type === 'income') return;

        $budget = Budget::where('category_id', $transaction->category_id)->first();
        if (!$budget) return;

        $startDate = $budget->period === 'monthly'
            ? now()->startOfMonth()
            : now()->startOfWeek();

        $endDate = $budget->period === 'monthly'
            ? now()->endOfMonth()
            : now()->endOfWeek();

        $totalSpent = Transaction::where('category_id', $transaction->category_id)
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        if ($totalSpent > $budget->limit_amount) {
            event(new BudgetExceeded($transaction, $budget, $totalSpent));
        }

    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // dd([
        //     'old_wallet_id' => $transaction->getOriginal('wallet_id'),
        //     'new_wallet_id' => $transaction->wallet_id,
        //     'old_amount'    => $transaction->getOriginal('amount'),
        //     'new_amount'    => $transaction->amount,
        //     'old_type'      => $transaction->getOriginal('type'),
        //     'new_type'      => $transaction->type,
        // ]);

        $oldWalletId = $transaction->getOriginal('wallet_id');
        $newWalletId = $transaction->wallet_id;

        $oldWallet = Wallet::find($oldWalletId);
        $newWallet = Wallet::find($newWalletId);

        $oldType    = $transaction->getOriginal('type');
        $oldAmount  = $transaction->getOriginal('amount');

        // Step 2 — undo the OLD transaction's effect on the OLD wallet
        if($oldType === 'expense'){
            $oldWallet->increment('balance', $oldAmount);
        }
        else{
            $oldWallet->decrement('balance', $oldAmount);
        }

        $newType    = $transaction->type;
        $newAmount  = $transaction->amount;
        if($newType === 'income'){
            $newWallet->increment('balance', $newAmount);
        }
        else{
            $newWallet->decrement('balance', $newAmount);
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        if($transaction->type === 'expense'){
            $transaction->wallet->increment('balance', $transaction->amount);
        }
        else{
            $transaction->wallet->decrement('balance', $transaction->amount);
        }
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
