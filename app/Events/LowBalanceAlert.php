<?php

namespace App\Events;

use App\Models\Wallet;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowBalanceAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Wallet $wallet) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User'. $this->wallet->user_id),
        ];
    }

    public function broadcastWith(){
        return [
            'wallet_id'             => $this->wallet->id,
            'name'                  => $this->wallet->name,
            'balance'               => $this->wallet->balance,
            'low_balance_threshold' => $this->wallet->low_balance_threshold,
            'currency'              => $this->wallet->currency
        ];
    }
}
