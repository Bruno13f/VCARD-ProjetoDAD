<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function getTransactionOfVcard(Vcard $vcard){
        return $vcard->transactions;
    }

    public function getCategoryOfTransaction(Category $category){
        return $category->transactions;
    }
}
