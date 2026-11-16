<?php

namespace App\Listeners;

use App\Enums\NotificationType;
use App\Events\BudgetExceeded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendBudgetExceededNotification
{

    public function __construct()
    {
        //
    }

    public function handle(BudgetExceeded $event): void
    {
        $event->budget->notifications()->create([
            'user_id'   => $event->budget->user_id,
            'type'      => NotificationType::BudgetExceeded,
            'message'   => 'Your budget for ' . $event->budget->category->name . ' has been exceeded.',
            'data'     => [
                'total_spent' => $event->totalSpent,
                'limit'       => $event->budget->limit_amount,
                'period'      => $event->budget->period,
            ],
        ]);
    }
}
