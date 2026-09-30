<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{

    public function definition(): array
    {
        return [
            'wallet_id' => Wallet::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'amount' => fake()->randomFloat(2, 1, 1000),
            'currency' => fake()->randomElement(['SYP','USD','EUR']),
            'date' => fake()->date(),
            'type' => fake()->randomElement(['income','expense']),
            'notes' => fake()->text()
        ];
    }
}
