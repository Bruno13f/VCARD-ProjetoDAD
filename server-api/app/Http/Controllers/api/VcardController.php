<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Models\DefaultCategory;
use App\Models\Category;
use App\Http\Resources\VcardResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVcardRequest;
use App\Http\Requests\UpdateVcardProfileRequest;
use App\Http\Requests\UpdateVcardRequest;
use App\Http\Requests\UpdateBlockVcardRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserConfirmationCodeRequest;
use App\Http\Requests\UpdateMaxDebitVcardRequest;
use Illuminate\Http\Response;
use App\Services\Base64Services;
use Illuminate\Support\Facades\Storage;

class VcardController extends Controller
{

    private function storeBase64AsFile(Vcard $vcard, String $base64String)
    {
        $targetDir = storage_path('app/public/fotos');
        $newfilename = $vcard->phone_number . "_" . rand(1000,9999);
        $base64Service = new Base64Services();
        return $base64Service->saveFile($base64String, $targetDir, $newfilename);
    }

    public function index()
    {
        return VcardResource::collection(VCard::all());
    }

    public function show (Vcard $vcard){
        return new VCardResource($vcard);
    }

    public function store(StoreVcardRequest $request){

        $validatedRequest = $request->validated();
        $validatedRequest['blocked'] = 0;
        $validatedRequest['max_debit'] = 2500;
        $validatedRequest['balance'] = 0;

        $newVcard = Vcard::create($validatedRequest); 

        //associar categorias ao vcard 

        $newVcard->phone_number=$validatedRequest['phone_number'];
        // ate dar fix - resource aparece com number a 0

        $defaultCategories = DefaultCategory::all();

        foreach ($defaultCategories as $defaultCategory) {
            // criar entrada na tabela category
            $category = new Category([
                'vcard' => $newVcard->phone_number,
                'type' => $defaultCategory->type,
                'name' => $defaultCategory->name
            ]);
            $category->save();
        }
        
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

    public function updateProfile (UpdateVcardProfileRequest $request, Vcard $vcard){

        $dataToSave = $request->validated();

        $base64ImagePhoto = array_key_exists("base64ImagePhoto", $dataToSave) ?
            $dataToSave["base64ImagePhoto"] : ($dataToSave["base64ImagePhoto"] ?? null);
        $deletePhotoOnServer = array_key_exists("deletePhotoOnServer", $dataToSave) && $dataToSave["deletePhotoOnServer"];
        unset($dataToSave["base64ImagePhoto"]);
        unset($dataToSave["deletePhotoOnServer"]);

        $vcard->name = $dataToSave['name'];
        $vcard->email = $request['email'];

        if ($vcard->photo_url && ($deletePhotoOnServer || $base64ImagePhoto)) {
            if (Storage::exists('public/fotos/' . $vcard->photo_url)) {
                Storage::delete('public/fotos/' . $vcard->photo_url);
            }
            $vcard->photo_url = null;
        }

        if ($base64ImagePhoto) {
            $vcard->photo_url = $this->storeBase64AsFile($vcard, $base64ImagePhoto);
        }

        $vcard->save();
        return new VcardResource($vcard);
    }


    public function destroy (Vcard $vcard){
        if ($vcard->balance != 0)
            return response()->json(['error' => "Can't delete the Vcard - Balance different than 0"], Response::HTTP_UNPROCESSABLE_ENTITY); // nao e possivel eliminar 

        // soft delete se tiver transacoes senao forceDelete
        
        if (count($vcard->transactions)){
            $vcard->delete();
        }else{
            $vcard->forceDelete();
        }
            
        return new VcardResource($vcard);
        
    }

    public function getTransactionsOfVcard(Vcard $vcard)
    {
        $transactions = $vcard->transactions()->orderBy('date', 'desc')->get();

        return TransactionResource::collection($transactions);
    }

    public function getCategoryOfVcard(Vcard $vcard){
        return CategoryResource::collection($vcard->categories);
    }


    public function update_password (UpdateUserPasswordRequest $request, Vcard $vcard)
    {
        $vcard->password = bcrypt($request->validated()['password']);
        $vcard->save();
        return new VcardResource($vcard);
    }

    public function update_confirmation_code (UpdateUserConfirmationCodeRequest $request, Vcard $vcard)
    {
        $vcard->confirmation_code = bcrypt($request->validated()['confirmation_code']);
        $vcard->save();
        return new VcardResource($vcard);
    }
}
