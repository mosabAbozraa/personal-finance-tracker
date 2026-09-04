<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function addBudget(Request $request, $categoryId){
        $user = Auth::user();
        $data = $request->validate([
            'limit_amount' => 'required | numeric | min:0',
            'period' => 'required | in: weekly, monthly',
        ]);
        $category = Category::findOrFail($categoryId);
        $budget = $category->budget->create([
            'limit_amount' => $data['limit_amount'],
            'period' => $data['period'],
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Budget added successfully',
            'budget' => $budget,
        ], 201);
    }

    public function updateBudget(Request $request, $budgetId){
        $request->validate([
            'limit_amount' => ['sometimes', 'numeric', 'min:0'],
            'period' => ['sometimes', 'in:weekly,monthly'],
        ]);

        $budget = Budget::where('user_id', Auth::user()->id)->findOrFail($budgetId);
        $newBudget = $budget->update($request->only(['limit_amount', 'period']));

        return response()->json([
            'message' => 'budget updated successfully',
            'new budget' => $newBudget
        ], 200);
    }

    public function removeBudget($budgetId){
        $budget = Budget::where('user_id', Auth::user()->id)->findOrFail($budgetId);
        $budget->delete();

        return response()->json('Budget deleted successfully', 200);
    }
}
