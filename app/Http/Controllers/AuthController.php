<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register (Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            ]
        );

        $user = user::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' =>bcrypt($fields['password']),
        ]);

        $token = $user->createToken('onesapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login (Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            ]
        );

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'sus'
            ], 401);
        }

        $token = $user->createToken('onesapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout (Request $request){
        $request->user()->tokens()->delete();

        return [
            'message' => 'anda logoutedd'
        ];
    }
}
