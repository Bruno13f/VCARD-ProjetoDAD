<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Http\Resources\VcardResource;
use App\Http\Resources\TransactionResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateVcardRequest;
use App\Http\Requests\StoreUpdateVcardRequest;
use App\Http\Requests\UpdateBlockVcardRequest;
use App\Http\Requests\UpdateMaxDebitVcardRequest;

class VcardController extends Controller
{
    public function index()
    {
        return VcardResource::collection(Vcard::all());
    }

    public function show (Vcard $vcard){
        return new VCardResource($vcard);
    }

    public function store(StoreUpdateVcardRequest $request){
        /*if (Vcard::where('phone_number', $request->phone_number)->exists()) {
            return response()->json(['error' => 'Phone number is already associated with a vcard'], 422);
        }*/
        $newVcard = Vcard::create($request->validated()); 
        return new VcardResource($newVcard);
    }

    public function update(UpdateVcardRequest $request, Vcard $vcard)
    {
        $vcard->update($request->validated());
        return new VcardResource($vcard);
    }

    public function updateMaxDebit(UpdateMaxDebitVcardRequest $request, Vcard $vcard)
    {
        $vcard->update($request->validated());
        return new VcardResource($vcard);
    }
    
    public function updateBlocked (UpdateBlockVcardRequest $request, Vcard $vcard){
        $vcard->blocked = $request->blocked;
        $vcard->save();
        return new VcardResource($vcard);
    }

    public function destroy (Vcard $vcard){
        if ($vcard->balance != 0)
            return; // nao e possivel eliminar 

        // soft delete se tiver transacoes senao forceDelete
        
        if ($vcard->transactions){
            $vcard->delete(); // soft delete
        }else{
            $vcard->forceDelete();
        }
            
        return new VCardResource($vcard);
        
    }

    public function getTransactionOfVcard(Vcard $vcard){
        return TransactionResource::collection($vcard->transactions);
    }
}
