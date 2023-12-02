<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{

    public function index()
    {
        //pedido no enunciado para estar ordenado de acordo com a data mais recente primeiro
        $transactions = Transaction::orderBy('created_at', 'desc')->get();

        return TransactionResource::collection($transactions);
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    public function store(StoreTransactionRequest $request)
    {
        $validatedRequest = $request->validated();

        $vcard = Vcard::findOrFail($validatedRequest['vcard']);

        $validatedRequest['old_balance'] = $vcard->balance;

        $validatedRequest['date'] = now()->toDateString();
        $validatedRequest['datetime'] = now();

        if ($validatedRequest['payment_type'] == 'VCARD' && $validatedRequest['type'] == 'D') {
            $vcardReceiver = Vcard::findOrFail($validatedRequest['payment_reference']);
            
            $vcardReceiver->balance += $validatedRequest['value'];
            $validatedRequest['new_balance'] = $vcard->balance -= $validatedRequest['value'];
            $vcard->save();
            $vcardReceiver->save();

        }else if($validatedRequest['payment_type'] == 'VCARD' && $validatedRequest['type'] == 'C'){
            $vcardReceiver = Vcard::findOrFail($validatedRequest['payment_reference']);

            $vcardReceiver->balance -= $validatedRequest['value'];
            $validatedRequest['new_balance'] = $vcard->balance += $validatedRequest['value'];
            $vcard->save();
            $vcardReceiver->save();
        }

        $newTransaction = $vcard->transactions()->create($validatedRequest);

        return new TransactionResource($newTransaction);
    }


    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());
        return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction)
    {

        // pode ser soft deleted se vcard soft deleted
        if ($transaction->vcardOfTransaction->trashed()) {
            $transaction->delete();
            return new TransactionResource($transaction);
        }

        return response()->json(['error' => "Can't delete tre transaction - Vcard exists"], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}