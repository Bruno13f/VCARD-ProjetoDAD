<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Http\Resources\VcardResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VcardController extends Controller
{
    public function index(){
        return VcardResource::collection(Vcard::all());  
    }
}
