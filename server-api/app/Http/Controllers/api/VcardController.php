<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Http\Resources\VcardResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateVcardRequest;
use App\Http\Requests\StoreUpdateVcardRequest;
use App\Http\Requests\UpdateBlockVcardRequest;

class VcardController extends Controller
{
    public function index()
    {
        return VcardResource::collection(Vcard::all());
    }

    public function update(UpdateVcardRequest $request, Vcard $vcard)
    {
        $vcard->update($request->validated());
        return new VcardResource($vcard);
    }

    public function show (Vcard $vcard){
        return new VCardResource($vcard);
    }

    public function store(StoreUpdateVcardRequest $request){
        /*if (Vcard::where('phone_number', $request->phone_number)->exists()) {
            return response()->json(['error' => 'Phone number is already associated with a vcard'], 422);
        }*/
        
        $newVcard = Vcard::create($request->validated()); 
        return new ($newVcard);
    }

    public function updateBlocked (UpdateBlockVcardRequest $request, Vcard $vcard){
        $vcard->blocked = $request->blocked;
        $vcard->save();
        return new VcardResource($vcard);
    }
}
