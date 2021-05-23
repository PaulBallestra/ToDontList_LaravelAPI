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
        $validated = $request->validate([
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
            'token' => $token
        ], 201);

    }

    public function login(Request $request){

        //Validation champs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        //Data CHECK
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['errors' => "Identifiant Inconnu"], 401);
        }

        //Suppresion de l'ancien token
        $user->tokens()->where('tokenable_id', $user->id)->delete();

        //Création du nouveau
        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'token' => $token
        ], 202);

    }

}
