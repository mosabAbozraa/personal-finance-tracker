<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function create_transaction(CreateTransactionRequest $request){
        $user = Auth::user();

        $wallet = Wallet::where('user_id', $user->id)->find($request->wallet_id);
        if(!$wallet){
            Log::error('Wallet Not Found', ['user_id' => $user->id]);
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $category = Category::where('user_id', $user->id)->find($request->category_id);
        if(!$category){
            Log::error('Category Not Found', ['user_id' => $user->id]);
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validatedData = $request->validated();
        $validatedData['wallet_id'] = $wallet->id;
        $validatedData['category_id'] = $category->id;
        $validatedData['date'] = $validatedData['date'] ?? now()->toDateString();

        $transaction = Transaction::create($validatedData);
        $transaction->wallet->refresh();

        Log::info('Transaction Created Successfully', [
            'user_id' => $user->id,
            'transaction_id' => $transaction->id,
        ]);

        return response()->json([
            'message' => $transaction->wallet->balance < 0
                ? 'Transaction created successfully. Warning: your wallet balance is now negative.'
                : 'Transaction created successfully',
            'transaction' => $transaction->load('wallet', 'category')
        ], 201);
    }

    public function update_transaction(UpdateTransactionRequest $request, $id){
        $user = Auth::user();

        $transaction = Transaction::whereHas('wallet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($id);

        $validatedData = $request->validated();

        $newWalletId = $validatedData['wallet_id'] ?? $transaction->wallet_id;
        Wallet::where('user_id', $user->id)->findOrFail($newWalletId);

        $newCategoryId = $validatedData['category_id'] ?? $transaction->category_id;
        Category::where('user_id', $user->id)->findOrFail($newCategoryId);

        $transaction->update($validatedData);
        $transaction->wallet->refresh();

        Log::info('Transaction Updated Successfully', [
            'user_id' => $user->id,
            'transaction_id' => $transaction->id,
        ]);

        return response()->json([
            'message' => $transaction->wallet->balance < 0
                ? 'Transaction updated successfully. Warning: your wallet balance is now negative.'
                : 'Transaction updated successfully',
            'transaction' => $transaction->load('wallet', 'category')
        ], 200);
    }

    public function delete_transaction($id){
        $user = Auth::user();
        $transaction = Transaction::whereHas('wallet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($id);
        Log::warning('Transaction Deleted', [
            'user_id' => Auth::id(),
            'transaction_id' => $transaction->id,
        ]);

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully'], 200);
    }
}
