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
use Illuminate\Support\Facades\Http;


class TransactionController extends Controller {

    public function index() {
        //pedido no enunciado para estar ordenado de acordo com a data mais recente primeiro
        $transactions = Transaction::orderBy('created_at', 'desc')->get();

        return TransactionResource::collection($transactions);
    }

    public function show(Transaction $transaction) {
        return new TransactionResource($transaction);
    }

    public function store(StoreTransactionRequest $request) {
        $validatedRequest = $request->validated();

        $vcard = Vcard::findOrFail($validatedRequest['vcard']);

        $validatedRequest['old_balance'] = $vcard->balance;

        if($validatedRequest['payment_type'] == 'VCARD') {
            $vcardReceiver = Vcard::findOrFail($validatedRequest['payment_reference']);
            $validatedRequest['pair_vcard'] = $vcardReceiver->phone_number;

            $createdTransaction = $this->createAdditionalTransaction($vcardReceiver, $validatedRequest);

            if($validatedRequest['type'] == 'D') {
                if(($vcard->balance - $validatedRequest['value']) < 0) {
                    return response()->json(['error' => "The vcard doesnt have enough money to complete the transaction"], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            } else if(($vcardReceiver->balance - $validatedRequest['value']) < 0) {
                return response()->json(['error' => "The vcard doesnt have enough money to complete the transaction"], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

        } else {
            $paymentServiceUrl = 'https://dad-202324-payments-api.vercel.app';
            $paymentServiceEndpoint = '/api/'.($validatedRequest['type'] == 'C' ? 'debit' : 'credit');
            $paymentServiceUrl = $paymentServiceUrl.$paymentServiceEndpoint;

            $paymentServicePayload = [
                'type' => $validatedRequest['payment_type'],
                'reference' => $validatedRequest['payment_reference'],
                'value' => (int)$validatedRequest['value'],
            ];

            $paymentServiceResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($paymentServiceUrl, $paymentServicePayload);

            if($paymentServiceResponse->successful()) {
                if($validatedRequest['type'] == 'D') {
                    $validatedRequest['new_balance'] = $vcard->balance -= $validatedRequest['value'];
                    $vcard->save();
                } else if($validatedRequest['type'] == 'C') {
                    $validatedRequest['new_balance'] = $vcard->balance += $validatedRequest['value'];
                    $vcard->save();
                }

            } else {
                $errorResponse = $paymentServiceUrl;
                return response()->json(['error' => $errorResponse], $paymentServiceResponse->status());
            }
        }

        if(($validatedRequest['vcard'] == $validatedRequest['payment_reference']) && $validatedRequest['payment_type'] == 'VCARD') {
            return response()->json(['error' => "You cant transfer money to yourself"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if($validatedRequest['value'] > $vcard->max_debit) {
            return response()->json(['error' => "The value of the transfer is greater that the vcard max_debit"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedRequest['date'] = now()->toDateString();
        $validatedRequest['datetime'] = now();

        if($validatedRequest['payment_type'] == 'VCARD' && $validatedRequest['type'] == 'D') {
            $vcardReceiver->balance += $validatedRequest['value'];
            $validatedRequest['new_balance'] = $vcard->balance -= $validatedRequest['value'];
            $vcard->save();
            $vcardReceiver->save();

        } else if($validatedRequest['payment_type'] == 'VCARD' && $validatedRequest['type'] == 'C') {
            $vcardReceiver->balance -= $validatedRequest['value'];
            $validatedRequest['new_balance'] = $vcard->balance += $validatedRequest['value'];
            $vcard->save();
            $vcardReceiver->save();
        }

        $newTransaction = $vcard->transactions()->create($validatedRequest);

        if($validatedRequest['payment_type'] == 'VCARD') {
            $createdTransaction['pair_transaction'] = $newTransaction->id;
            $newTransaction['pair_transaction'] = $createdTransaction->id;
            $createdTransaction->save();
            $newTransaction->save();
        }

        return new TransactionResource($newTransaction);
    }

    private function createAdditionalTransaction(Vcard $vcardReceiver, array $validatedRequest) {
        $additionalTransaction = new Transaction([
            'vcard' => $validatedRequest['payment_reference'],
            'value' => $validatedRequest['value'],
            'type' => $validatedRequest['type'] == 'C' ? 'D' : 'C',
            'payment_reference' => $validatedRequest['vcard'],
            'old_balance' => $vcardReceiver->balance,
            'date' => now()->toDateString(),
            'datetime' => now(),
            'new_balance' => $validatedRequest['type'] == 'C' ? $vcardReceiver->balance - $validatedRequest['value'] : $vcardReceiver->balance + $validatedRequest['value'],
            'pair_vcard' => $validatedRequest['vcard'],
            'payment_type' => $validatedRequest['payment_type'],
        ]);
        $additionalTransaction->save();

        return $additionalTransaction;
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction) {
        $transaction->update($request->validated());
        return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction) {

        // pode ser soft deleted se vcard soft deleted
        if($transaction->vcardOfTransaction->trashed()) {
            $transaction->delete();
            return new TransactionResource($transaction);
        }

        return response()->json(['error' => "Can't delete tre transaction - Vcard exists"], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}