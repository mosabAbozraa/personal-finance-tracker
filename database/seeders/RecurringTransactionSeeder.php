<?php

namespace Database\Seeders;

use App\Models\RecurringTransaction;
use Illuminate\Database\Seeder;

class RecurringTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RecurringTransaction::factory()->count(10)->create();
    }
}
