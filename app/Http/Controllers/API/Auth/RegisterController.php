<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle a registration attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function register(Request $request)
    {
        $credentialsValidated = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed',
        ]);
        
        if ($credentialsValidated->fails()) {
            return response()->json($credentialsValidated->errors(), 401);
        }

        $user = User::create([
            'name' => $request->username, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'email' => $user->email,
        ], 201);
    }
}
