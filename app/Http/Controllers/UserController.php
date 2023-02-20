<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserData;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = User::find($request->id)->load('department','service','userData');
        return response()->json(new UserResource($user),200);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'email' => 'required|string',
            'type' => 'required|string',
            'isActive' => 'required|boolean',
            'name' => 'required|string',
            'firstSurname' => 'required|string',
            'department' => 'required|integer',
        ],);

        DB::beginTransaction();

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'type' => $request->type,
            'is_active' => $request->isActive,
            'department_id' => $request->department,
        ]);

        if(!$user){
            DB::rollBack();
            return response()->json(['error' => 'Error creating user'], 500);
        }

        $userData = $user->userData()->create([
            'name' => $request->name,
            'created' => now(),
            'first_surname' => $request->firstSurname,
            'second_surname' => $request->secondSurname ? $request->secondSurname : null,
            'user_id' => $user->id,
        ]);

        if(!$userData){
            DB::rollBack();
            return response()->json(['error' => 'Error creating user data'], 500);
        }

        DB::commit();

        return response()->json(new UserResource($user),200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'username' => 'required|string',
            'email' => 'required|string',
            'type' => 'required|string',
            'isActive' => 'required|boolean',
            'name' => 'required|string',
            'firstSurname' => 'required|string',
            'department' => 'required|integer',
        ],);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        User::where('id',$request->id)
            ->update([
                'username' => $request->username,
                'email' => $request->email,
                'type' => $request->type,
                'is_active' => $request->isActive,
                'department_id' => $request->department,
            ]);

        UserData::where('user_id',$request->id)
            ->update([
                'name' => $request->name,
                'first_surname' => $request->firstSurname,
                'second_surname' => $request->secondSurname ? $request->secondSurname : null,
            ]);
        
        $user = User::find($request->id)->load('department','service','userData');

        return response()->json(new UserResource($user),200);
    }


    public function lockUser(Request $request)
    {
        $user = User::find($request->id);
        $user->is_active = $request->isActive;
        $user->save();
        return response()->json(new UserResource($user),200);
    }

}
