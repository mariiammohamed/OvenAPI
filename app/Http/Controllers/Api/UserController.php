<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Auth facade for authentication


class UserController extends Controller
{
    //register , login , profile , logout


    public function register(Request $request){
        $validatorUser = validator::make($request->all(),
        [   'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',

        ]);
        if($validatorUser->fails()){
            return response()->json([
                'status'=> false,
                'message'=>"validator error",
                'errors' =>$validatorUser->errors()
            ]);
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password
        ]);

        return response()->json([
            'status'=>true,
            'message'=> "User Created Successfully",
            'token' => $user->createToken('API Token')->plainTextToken
        ], 200);

    }

    public function login(Request $request){

        $validationUser = validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string'

        ]);

        if($validationUser->fails()){
            return response()->json([
                'status' => false,
                'message' => "Unprocessable Entity",
            ], 422); // HTTP status code 422 for Unprocessable Entity
        }

        if(!Auth::attempt($request->only('email' , 'password'))){
            return response()->json([
                'status' => false,
                'message' => "Unauthorized User",

            ], 401) ;// HTTP status code 401 for Unauthorized
        }
        $user = Auth::User();

        return response()->json([
            'status'=> true,
            'message' => "You are Login Successfully",
            'token' => $user->createToken('API Token')->plainTextToken
        ], 200);

    }
    public function profile(){
        $user = auth()->user();
        return response()->json([
            'status' => true,
            'message' => 'Your Profile Data',
            'data' => $user,
            'ID' => $user->id
        ], 200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => "You are Logout Successfuly",
        ], 200);
    }

}
