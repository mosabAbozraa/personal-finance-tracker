<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecurringTransaction>
 */
class RecurringTransactionFactory extends Factory
{

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'wallet_id' => Wallet::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'amount' => fake()->randomFloat(2, 10, 1000),
            'type' => fake()->randomElement(['income', 'expense']),
            'frequency' => fake()->randomElement(['daily', 'weekly', 'monthly']),
            'next_run_at' => fake()->dateTimeBetween('now', '+1 year'),
            'is_active' => fake()->boolean(),
            'notes' => fake()->sentence(),
        ];
    }
}
