<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Resources\TransactionResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function index(){
        //pedido no enunciado para estar ordenado de acordo com a data mais recente primeiro
        $transactions = Transaction::orderBy('created_at', 'desc')->get();

        return TransactionResource::collection($transactions);
    }

    public function show (Transaction $transaction){
        return new TransactionResource($transaction);
    }

    public function update(UpdateTransaction $request, Transaction $transaction)
    {
        $transaction->update($request->validated());
        return new TransactionResource($transaction);
    }

    public function destroy (Transaction $transaction){
        
        // pode ser soft deleted se vcard soft deleted
        if ($transaction->vcardOfTransaction->trashed()){
            $transaction->delete();
            return new TransactionResource($transaction);
        }

        return; //nao pode ser soft deleted
    }
}