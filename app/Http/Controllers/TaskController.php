<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/task",
     *     tags={"Tarefas"},
     *     summary="Criando nova tarefa",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema( required={"assigneduser_id" , "title", "description", "due", "completed"},
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
      *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"assigneduser_id": 1, "title": "New task", "description": "loren impsum", "due": "2024-04-11", "completed": false}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task Created Successfully",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":true,"message":"Task Created Successfully","task":{"assigneduser_id":1,"title":"New task","description":"loren impsum","due":"2024-04-11","completed":0,"taskcreator_id":1,"updated_at":"2024-04-16T00:40:56.000000Z","created_at":"2024-04-16T00:40:56.000000Z","id":8}}, summary="An result object."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation request",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":false,"message":"validation error","errors":{"fields":{"The assigneduser_id not found."}}}, summary="An result object."),
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":false,"message":"Throwable errors"}, summary="An result object."),
     *         )
     *     )
     * )
     */    
    public function store(Request $request)
    {        
        try {
            $validateTask = Validator::make($request->all(), 
            [
                'title' => 'required',
                'due' => 'required',
                'description' => 'required',
                'assigneduser_id' => ['required', Rule::exists('users', 'id')]
            ]);

            if($validateTask->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateTask->errors()
                ], 401);
            }

            $attributes = $request->all();
            $attributes['taskcreator_id'] =  Auth::user()->id;
            $attributes['completed'] = 0;

            $task = Task::create($attributes);

            return response()->json([
                'status' => true,
                'message' => 'Task Created Successfully',
                'task' => $task
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"Tarefas"},
     *     summary="Pegando todas as tarefas",
     *     description="Para o retorno dos dados, deve-se autenticar e pegar o token gerado. Clicar no cadeado e colocar Bearer mais o token gerado no login. Ex: Bearer 12|KvkT1313lSelWBH5xXxokLYOhN38B3XsS3riNuLKcca49988",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Get all successfully",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"id":1,"taskcreator_id":1,"assigneduser_id":1,"title":"New task","description":"loren impsum","due":"2024-04-11 00:00:00","completed":0,"created_at":"2024-04-15T20:24:28.000000Z","updated_at":"2024-04-15T20:24:28.000000Z"}, summary="An result object."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message": "Unauthenticated."}, summary="An result object."),
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":false,"message":"Throwable errors"}, summary="An result object."),
     *         )
     *     )
     * )
     */
    public function tasks()
    {
        return $tasks = Task::all();
    }

    /**
     * @OA\Post(
     *     path="/api/task/update",
     *     tags={"Tarefas"},
     *     summary="Alterando uma tarefa",
     *     description="Deve-se autenticar e pegar o token gerado. Clicar no cadeado e colocar Bearer mais o token gerado no login. Ex: Bearer 12|KvkT1313lSelWBH5xXxokLYOhN38B3XsS3riNuLKcca49988",
     *     security={{"sanctum":{}}}, 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="assigneduser_id",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 ),
    *                 @OA\Property(
     *                     property="due",
     *                     type="date"
     *                 ),
     *                 example={"assigneduser_id":1,"title":"New task","description":"loren impsum","due":"2024-04-12"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task Updated Successfully",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":true,"message":"Task Updated Successfully","task":{"id":1,"taskcreator_id":1,"assigneduser_id":1,"title":"New task","description":"loren impsum","due":"2024-04-12","completed":0,"created_at":"2024-04-15T20:24:28.000000Z","updated_at":"2024-04-16T00:42:40.000000Z"}}, summary="An result object."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":false,"message":"validation error","errors":{"email":{"The email has already been taken."}}}, summary="An result object."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":false,"message":"User not found"}, summary="An result object."),
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":false,"message":"Throwable errors"}, summary="An result object."),
     *         )
     *     )
     * )
     */    
    public function update(Request $request)
    {
         try {
            $validateTask = Validator::make($request->all(), 
            [
                'title' => 'required',
                'due' => 'required',
                'description' => 'required',
                'assigneduser_id' => ['required', Rule::exists('users', 'id')]
            ]);

            if($validateTask->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateTask->errors()
                ], 401);
            }

            $attributes = $request->all();   
       
            $attributes['taskcreator_id'] =  Auth::user()->id;
            $attributes['completed'] = 0;

            $task = Task::updateOrCreate(
                ['id' => $request->id],
                $attributes
            );

            return response()->json([
                'status'    => true,
                'message'   => 'Task Updated Successfully',
                'task'      => $task
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function validateTask(Request $request)
    {
        $attributes = $request->validate([
            'title' => 'required',
            'due' => 'required',
            'description' => 'required',
            'assigneduser_id' => ['required', Rule::exists('users', 'id')]
        ]);

        return $attributes;
    }
     /**
     * @OA\Post(
     *     path="/api/task/{task}/finished",
     *     tags={"Tarefas"},
     *     summary="Close a task",
     *     @OA\Parameter(
     *         description="Id da task",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function finished($id)
    {
        try {
            $task = Task::find($id);
            $task->completed = 1;
            $task->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Task Updated Successfully',
                'task'      => $task
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        
    }

    
}