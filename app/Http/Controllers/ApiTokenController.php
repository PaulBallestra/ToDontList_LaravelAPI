<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException;

class ApiTokenController extends Controller
{

    //FONCTION REGISTER
    public function register(Request $request){

        //User validation champs 422
        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required'
        ]);

        //Check if user exists
        $exists = User::where('email', $request->email)->exists();

        //Si l'user existe 409
        if($exists){
            return response()->json(['errors' => "Utilisateur déjà inscrit"], 409);
        }

        //Create USER
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);

        //Create TOKEN
        $token = $user->createToken($request->email)->plainTextToken;

        //Return response status 201
        return response()->json([
            'token' => $token
        ], 201);

    }

    //FUCNTION LOGIN
    public function login(Request $request){

        //Validation champs 422
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        //Data CHECK
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['errors' => "Identifiants inconnus ou erronés"], 401);
        }

        //Suppresion de l'ancien token
        $user->tokens()->where('tokenable_id', $user->id)->delete();

        //Création du nouveau
        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at
        ], 200);

    }

    //FUNCTION LOGOUT
    public function logout(Request $request){

        //401 UNAUTHENTICATED GÉRÉ PAR SANCTUM

        //Suppresion du token
        $request->user()->currentAccessToken()->delete();

        //status déconnecté 204
        return response(null, 204);

    }

    //FUNCTION ME - profil
    public function me(Request $request){

        //401 UNAUTHENTICATED GÉRÉ PAR SANCTUM

        //200 COOL
        return response()->json([
            'id' => $request->user()->id,
            'created_at' => $request->user()->created_at,
            'updated_at' => $request->user()->updated_at,
            'name' => $request->user()->name,
            'email' => $request->user()->email
        ], 200);

    }

}
