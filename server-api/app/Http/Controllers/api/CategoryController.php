<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(){
        return CategoryResource::collection(Category::all());
    }

    public function getCategoryOfVcard(Vcard $vcard){
        return $vcard->categories;
    }

    public function getCategoryOfTransaction(Transaction $transaction){
        return $transaction->categories;
    }
}