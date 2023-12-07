<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return UserResource::collection(User::paginate(15)); 
    }
    
    public function show(User $user){
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {   
        $user->update($request->validated());
        return new UserResource($user);
    }

    public function show_me(Request $request)
    {
        return new UserResource($request->user());
    }
    

}
