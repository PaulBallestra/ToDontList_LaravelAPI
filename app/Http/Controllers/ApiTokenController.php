<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException;

class ApiTokenController extends Controller
{

    public function register(Request $request){

        //User validation champs
        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required'
        ]);

        //Check if user exists
        $exists = User::where('email', $request->email)->exists();

        if($exists){
            return response()->json(['error' => "Utilisateur déjà inscrit"], 409);
        }

        //Create USER
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);

        //Create TOKEN
        $token = $user->createToken($request->email)->plainTextToken;

        //Return response
        return response()->json([
            'token' => $token,
            'email' => $user->email,
            'name' => $user->name
        ], 201);

    }

}
