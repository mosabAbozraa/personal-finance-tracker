<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes, HasFactory;
    protected $guarded = [];

    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
