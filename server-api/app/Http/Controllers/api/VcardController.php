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
use Illuminate\Http\Response;

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

        $validatedRequest = $request->validated();
        $validatedRequest['blocked'] = 0;
        $validatedRequest['max_debit'] = 2500;

        $newVcard = Vcard::create($validatedRequest); 

        $newVcard->phone_number=$validatedRequest['phone_number'];
        // ate dar fix - resource aparece com number a 0
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
            return response()->json(['error' => "Can't delete the Vcard - Balance different than 0"], Response::HTTP_UNPROCESSABLE_ENTITY); // nao e possivel eliminar 

        // soft delete se tiver transacoes senao forceDelete
        
        if ($vcard->transactions){
            $vcard->delete(); // soft delete
        }else{
            $vcard->forceDelete();
        }
            
        return new VCardResource($vcard);
        
    }

    public function getTransactiosnOfVcard(Vcard $vcard){
        return TransactionResource::collection($vcard->transactions);
    }
}
