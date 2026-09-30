<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRecurringTransactionRequest;
use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecurringTransactionsController extends Controller
{

    public function index(){
        $user = Auth::user();
        $recurringTransactions = RecurringTransaction::where('user_id', $user->id)->get();

        return response()->json([
            'message' => 'Recurring transactions retrieved successfully',
            'data'  => $recurringTransactions
        ], 200);
    }

    public function show($id){
        $user = Auth::user();
        $recurringTransactions = RecurringTransaction::find($id);

        if (!$recurringTransactions) {
            return response()->json(['message' => 'Recurring transaction not found'], 404);
        }
        $this->authorize('view', $recurringTransactions);

        return response()->json([
            'message' => 'Recurring transactions retrieved successfully',
            'data'  => $recurringTransactions
        ], 200);
    }

    public function store(Request $request){
        $user = Auth::user();
        $data = $request->validated();

        $wallet = Wallet::where('user_id', $user->id)->find($data['wallet_id']);
        if(!$wallet){
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $category = Category::where('user_id', $user->id)->find($data['category_id']);
        if(!$category){
            return response()->json(['message' => 'Category not found'], 404);
        }

        $data['next_run_at'] = $data['next_run_at'] ?? now()->toDateString();
        $data['user_id'] = $user->id;
        $recurring = RecurringTransaction::create($data);

        return response()->json([
            'message' => 'Transaction set for automatic recurring',
            'data'  => $recurring
        ], 201);
    }

    public function update(UpdateRecurringTransactionRequest $request, $id){
        $user = Auth::user();
        $recurring = RecurringTransaction::find($id);

        if (!$recurring) {
            return response()->json(['message' => 'Recurring transaction not found'], 404);
        }

        $this->authorize('update', $recurring);

        $data = $request->validated();

        if (isset($data['wallet_id'])) {
            $wallet = Wallet::where('user_id', $user->id)->find($data['wallet_id']);
            if (!$wallet) {
                return response()->json(['message' => 'Wallet not found for this user'], 404);
            }
        }

        if (isset($data['category_id'])) {
            $category = Category::where('user_id', $user->id)->find($data['category_id']);
            if (!$category) {
                return response()->json(['message' => 'Category not found for this user'], 404);
            }
        }

        $recurring->update($data);

        return response()->json([
            'message' => 'Recurring transaction updated successfully',
            'data' => $recurring
        ], 200);
    }

    public function destroy($id){
        $user = Auth::user();
        $recurring = RecurringTransaction::find($id);
        if (!$recurring) {
            return response()->json(['message' => 'Recurring transaction not found'], 404);
        }

        $this->authorize('delete', $recurring);

        $recurring->delete();

        return response()->json([
            'message' => 'Recurring transaction deleted successfully'
        ], 200);
    }
}
