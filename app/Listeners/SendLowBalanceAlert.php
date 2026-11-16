<?php

namespace App\Listeners;

use App\Enums\NotificationType;
use App\Events\LowBalanceAlert;

class SendLowBalanceAlert
{
    public function __construct()
    {
        //
    }

    public function handle(LowBalanceAlert $event): void
    {
        $event->wallet->notifications()->create([
            'user_id' => $event->wallet->user_id,
            'type' => NotificationType::LowBalance,
            'message' => "Your wallet '{$event->wallet->name}' has a low balance.",
            'data' => [
                'balance' => $event->wallet->balance,
                'low_balance_threshold' => $event->wallet->low_balance_threshold,
                'currency' => $event->wallet->currency
            ]
        ]);
    }
}
