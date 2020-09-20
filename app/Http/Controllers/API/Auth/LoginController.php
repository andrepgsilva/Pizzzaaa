<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Catch_;

class LoginController extends Controller
{
     /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            
            return response()->json([
                'user' => [
                    'username'=> $user->name, 
                    'email' => $user->email,
                    'profilePhotoPath' => $user->profile_photo_path
                ]
            ], 200);
        }

        return response()->json(['email' => ['Email or password wrong']], 401);
    }

     /**
     * Handle a logout attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function logout(Request $request)
    {
        try {
            Auth::guard('web')->logout();

            return response()->json('The user was successfully logout.');
        } catch(Except $e) {
            return response()->json('Something went wrong.', 500);
        }
    }
}
