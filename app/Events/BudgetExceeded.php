<?php

namespace App\Events;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BudgetExceeded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Transaction $transaction,
        public Budget $budget,
        public float $totalSpent
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User'.$this->budget->user_id),
        ];
    }

    public function broadcastWith(){
        return [
            'budget_id'    => $this->budget->id,
            'category'     => $this->budget->category->name,
            'limit_amount' => $this->budget->limit_amount,
            'total_spent'  => $this->totalSpent,
            'period'       => $this->budget->period,
        ];
    }
}
