<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    //FUNCTION CREATE - nouvelle tache
    public function create(Request $request){
        //401 GÉRÉ PAR SANCTUM
        //422 GÉRÉ PAR SANCTUM
        $request->validate([
            'body' => 'required'
        ]);

        //Création de la tache avec le body + id de l'user
        $task = Task::create([
            'body' => $request->body,
            'user_id' => $request->user()->id
        ]);

        //STATUS 201
        return response()->json([
            'id' => $task->id,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
            'body' => $task->body,
            'done' => 0,
            'user' => [
                'id' => $request->user()->id,
                'created_at' => $request->user()->created_at,
                'updated_at' => $request->user()->updated_at,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ]
        ], 201);

    }

    //FUNCTION SHOW ALL TASK OF USER
    public function showAll(Request $request){

        //401 UNAUTHENTICATED GÉRÉ PAR SANCTUM

        $tasks = Task::where('user_id', $request->user()->id)->get();

        return response()->json([
            'tasks' => $tasks
        ], 201);

    }
}
