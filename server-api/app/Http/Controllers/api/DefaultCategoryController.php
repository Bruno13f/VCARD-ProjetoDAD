<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DefaultCategory;
use App\Http\Resources\DefaultCategoryResource;

class DefaultCategoryController extends Controller
{
    public function index(){
        return DefaultCategoryResource::collection(DefaultCategory::all());
    }
}
