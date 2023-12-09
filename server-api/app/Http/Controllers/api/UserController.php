<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        if ($request->paginate == '0')
            return UserResource::collection(User::all());
    
        $userQuery = User::query();

        $userType = $request->userType;
        $blocked = $request->blocked;
        $order = $request->order;

        if ($userType != null)
            $userQuery->where('user_type', $userType);

        if ($blocked != null)
            $userQuery->where('blocked', $blocked);

        if ($order != null)
            $userQuery->orderBy('name', $order);
        
        
        return UserResource::collection($userQuery->paginate(15));
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
