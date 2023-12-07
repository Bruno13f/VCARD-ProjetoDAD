<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(){
        return CategoryResource::collection(Category::all());
    }

    public function show(Category $category){
        return new CategoryResource($category);
    }

    public function store(StoreCategoryRequest $request){
        $validatedRequest = $request->validated();

        $newCategory = Category::create($validatedRequest); 

        return new CategoryResource($newCategory)  ;
    }

    public function update(UpdateCategoryRequest $request, Category $category){
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    public function delete(Category $category){
        // soft delete se tiver a ser usada por uma categoria, senao full delete da db
        count($category->transactions) != 0 ? $category->delete() : $category->forceDelete();

        return new CategoryResource($category);
    }

    // public function getCategoryOfTransaction(Transaction $transaction){
    //     return $transaction->categories;
    // }
}