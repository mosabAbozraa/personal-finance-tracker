<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletRequest;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    public function add_wallet(WalletRequest $request){
        $user_id = Auth::id();
        $validatedData = $request->validated();
        $validatedData['user_id'] = $user_id;
        $wallet = Wallet::create($validatedData);

        Log::info('Wallet created', [
            'user_id' => $user_id, 'wallet_id' => $wallet->id
        ]);

        return response()->json([
            'message'   => 'wallet created successfully',
            'wallet'    => $wallet
        ], 201);
    }

    public function get_wallets(){
        $user = Auth::user();
        $wallets = $user->wallets;
        $count = $wallets->count();

        return response()->json([
            'message'  => 'You have ' . $count . ' wallets.',
            'wallets ' => $wallets
        ], 200);
    }

    public function get_one_wallet($id){
        $wallet = Wallet::where('user_id',Auth::id())->find($id);
        if($wallet === null){
            return response()->json('Wallet not found', 404);
        }

        return response()->json([
            'wallet' => $wallet
        ], 200);
    }

    public function update_wallet(Request $request, $id){
        $validatedData = $request->validate([
            'name'  => 'sometimes|string|max:50'
        ]);
        $wallet = Wallet::where('user_id',Auth::id())->find($id);
        if($wallet === null){
            return response()->json('Wallet not found', 404);
        }
        $wallet->update($validatedData);

        return response()->json([
            'message'   => 'wallet updated successfully',
            'wallet'    => $wallet
        ], 200);
    }

    public function delete_wallet($id){
        $wallet = Wallet::where('user_id',Auth::id())->find($id);
        if($wallet === null){
            return response()->json('Wallet not found', 404);
        }

        Log::warning('Wallet deleted', [
            'user_id' => Auth::id(), 'wallet_id' => $wallet->id
        ]);

        $wallet->delete();

        return response()->json(null, 204);
    }
}
