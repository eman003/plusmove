<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\V1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($credentials['password'],$user->password)) {
            return response()->json(
                ['message' => 'Invalid Credentials'],
                Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken($user->full_name.'-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ]);
    }
}
