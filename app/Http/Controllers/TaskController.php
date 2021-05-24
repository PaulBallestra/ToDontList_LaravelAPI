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

    //FUNCTION CUSTOM SHOW TASK ID
    public function show(Request $request, $id){

        //401 UNAUTHENTICATED GÉRÉ PAR SANCTUM

        //On recup la tache
        $ifTaskExists = Task::where('id', $id)->exists();

        //CHECK IF EXIST 404
        if(!$ifTaskExists){
            return response()->json([
                'errors' => "La tâche n'existe pas."
            ], 404);
        }

        $task = Task::where('id', $id)->first();

        //CHECK IF USER CAN USE THAT TASK 403
        if($task->user_id !== $request->user()->id){
            return response()->json([
                'errors' => "Accès à la tâche non autorisé."
            ], 403);
        }

        //STATUS 200 COOL
        return response()->json([
            'id' => $task->id,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
            'body' => $task->body,
            'done' => $task->done,
            'user' => [
                'id' => $request->user()->id,
                'created_at' => $request->user()->created_at,
                'updated_at' => $request->user()->updated_at,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ]
        ], 200);

    }

    //FUNCTION DELETE TASK ID
    public function delete(Request $request, $id){

        //401 UNAUTHENTICATED GÉRÉ PAR SANCTUM

        //On recup la tache
        $ifTaskExists = Task::where('id', $id)->exists();

        //CHECK IF EXIST 404
        if(!$ifTaskExists){
            return response()->json([
                'errors' => "La tâche n'existe pas."
            ], 404);
        }

        $task = Task::where('id', $id)->first();

        //CHECK IF USER CAN DELETE THAT TASK 403
        if($task->user_id !== $request->user()->id){
            return response()->json([
                'errors' => "Accès à la tâche non autorisé."
            ], 403);
        }

        //DELETE DE LA TASK 200
        Task::where('id', $id)->first()->delete();

        return response()->json([
            'id' => $task->id,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
            'body' => $task->body,
            'done' => $task->done,
            'user' => [
                'id' => $request->user()->id,
                'created_at' => $request->user()->created_at,
                'updated_at' => $request->user()->updated_at,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ]
        ], 200);

    }

    //FUNCTION UPDATE TASK ID
    public function update(Request $request, $id){

        //401 UNAUTHENTICATED GÉRÉ PAR SANCTUM

        //422 GÉRÉ PAR SANCTUM
        $request->validate([
            'body' => 'required'
        ]);

        //On recup la tache
        $ifTaskExists = Task::where('id', $id)->exists();

        //CHECK IF EXIST 404
        if(!$ifTaskExists){
            return response()->json([
                'errors' => "La tâche n'existe pas."
            ], 404);
        }

        $task = Task::where('id', $id)->first();

        //CHECK IF USER CAN USE THAT TASK 403
        if($task->user_id !== $request->user()->id){
            return response()->json([
                'errors' => "Accès à la tâche non autorisé."
            ], 403);
        }

        //Update de la task
        $updatedTask = Task::find($id);


        $updatedTask->body = $request->body;
        $updatedTask->save();

        //STATUS 200 COOL
        return response()->json([
            'id' => $updatedTask->id,
            'created_at' => $updatedTask->created_at,
            'updated_at' => $updatedTask->updated_at,
            'body' => $updatedTask->body,
            'done' => $updatedTask->done,
            'user' => [
                'id' => $request->user()->id,
                'created_at' => $request->user()->created_at,
                'updated_at' => $request->user()->updated_at,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ]
        ], 200);

    }
}
