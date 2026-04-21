<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Wallet;

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
