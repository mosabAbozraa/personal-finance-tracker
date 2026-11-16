<?php

namespace App\Providers;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Transaction::observe(TransactionObserver::class);
        Relation::morphmap([
            'wallet' => Wallet::class,
            'budget' => Budget::class,
            'transaction' => Transaction::class
        ]);
    }
}
