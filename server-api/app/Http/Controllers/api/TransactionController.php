<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Resources\TransactionResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function index(){
        return TransactionResource::collection(Transaction::all());
    }

    public function show (Transaction $transaction){
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