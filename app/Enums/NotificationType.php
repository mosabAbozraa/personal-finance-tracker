<?php

namespace App\Enums;

enum NotificationType: string
{
    case BudgetExceeded = 'budget_exceeded';
    case LowBalance     = 'low_balance';
}
