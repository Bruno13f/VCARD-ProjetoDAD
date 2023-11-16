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

    public function getTransactionOfVcard(Vcard $vcard){
        return $vcard->transactions;
    }
}