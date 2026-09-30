<?php

namespace App\Services;

use App\Models\Transaction;

class BudgetExceedService
{
    public function checkBudgetExceed($budget, $transaction){
        $startDate = $budget->period === 'monthly'
            ? now()->startOfMonth()
            : now()->startOfWeek();

        $endDate = $budget->period === 'monthly'
            ? now()->endOfMonth()
            : now()->endOfWeek();

        $totalSpent = Transaction::where('category_id', $transaction->category_id)
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return $totalSpent;
    }
}
