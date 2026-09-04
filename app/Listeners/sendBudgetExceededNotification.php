<?php

namespace App\Listeners;

use App\Events\BudgetExceeded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class sendBudgetExceededNotification
{

    public function __construct()
    {
        //
    }

    public function handle(BudgetExceeded $event): void
    {
        Log::warning('Budget limit exceeded for category ' . $event->budget->category->name . ' with total spent: ' . $event->totalSpent,[
            'user_id'     => $event->transaction->wallet->user_id,
            'category_id' => $event->transaction->category_id,
            'budget_id'   => $event->budget->id,
            'total_spent' => $event->totalSpent,
            'limit'       => $event->budget->limit_amount,
        ]);
    }
}
