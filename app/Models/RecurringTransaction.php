<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringTransaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'next_run_at' => 'date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function wallet(){
        return $this->belongsTo(Wallet::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function calculateNextRun(){
        $frequency = $this->frequency;
        switch($frequency){
            case 'daily': $this->next_run_at = $this->next_run_at->addDay(); break;
            case 'weekly': $this->next_run_at = $this->next_run_at->addWeek(); break;
            case 'monthly': $this->next_run_at = $this->next_run_at->addMonth(); break;
        }
        $this->save();
    }
}
