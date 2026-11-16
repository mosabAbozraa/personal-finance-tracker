<?php

namespace App\Models;

use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\morphTo;

class Notification extends Model
{
    protected $guarded = [];
    protected $casts = [
        'read_at' => 'datetime',
        'data' => 'array',
        'type' => NotificationType::class,
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function notifiable(){
        return $this->morphTo();
    }
}
