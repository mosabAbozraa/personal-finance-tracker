<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function Illuminate\Support\now;

class TransactionController extends Controller
{
    public function create_transation(CreateTransactionRequest $request){
        $user = Auth::user();
        $wallet = Wallet::where('user_id',$user->id)->find($request->wallet_id);
        if(!$wallet){
            Log::error('Wallet Not Found',[
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]);
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $category = Category::where('user_id',$user->id)->find($request->category_id);
        if(!$category){
            Log::error('Category Not Found',[
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]);
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validatedData = $request->validated();
        $validatedData['wallet_id'] = $wallet->id;
        $validatedData['category_id'] = $category->id;
        $validatedData['date'] = $validatedData['date'] ?? now()->toDateString();
        echo 'still here\n';
        $transaction = Transaction::create($validatedData);
        echo 'still here\n';

        //      Update wallet balance
        $newBalance = $request->type ==='income' ? $wallet->balance + $request->amount : $wallet->balance - $request->amount;
        $wallet->update(['balance'=>$newBalance]);

        Log::info('Transaction Created Successfully',[
            'user_id' => $user->id,
            'transaction_id' => $transaction->id,
        ]);
        return response()->json([
            'message' => $newBalance < 0 ?
            'Transaction created successfully. Warning: your wallet balance is now negative.' :
            'Transaction created successfully',
            'transaction' => $transaction->load('wallet','category')
        ], 201);
    }
}
