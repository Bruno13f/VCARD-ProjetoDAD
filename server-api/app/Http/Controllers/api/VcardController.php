<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Http\Resources\VcardResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateVcardRequest;

class VcardController extends Controller
{
    public function index(){
        return VcardResource::collection(Vcard::all());  
    }

    public function store(StoreUpdateVcardRequest $request)
    {
        $newVcard = Vcard::create($request->validated());
        return new VcardResource($newVcard);
    }
}
