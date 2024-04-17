<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Usuários"},
     *     summary="Criando novo usuário",
     *     @OA\Property(
     * 		property="status",
     * 		type="string"
     * 	    ),
     * 	    @OA\Property(
     * 		property="error",
     * 		type="string"
     * 	),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema( required={"name" , "email", "password"},
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
     *                 example={"name": "Rafael", "email": "rafael.frotac@gmail.com", "password": "teste"}
     *             )
     *         )
     *     ),          
     *     @OA\Response(
     *         response="200",
     *         description="User Created Successfully",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="token",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "status": true,
     *                         "message": "User Created Successfully",
     *                         "token": "Bearer 8|KpIWQ5NCinZmPXszvViEJONX038iYaEQ4xzVT8hDe1b0d69e"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation request",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="validation error"
     *                     ),
     *                     @OA\Property(
     *                         property="errors",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "status": false,
     *                         "message": "validation error",
     *                         "errors":{"email":{"The email has already been taken."}}
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="500 Internal Server Error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     example={
     *                         "status": true,
     *                         "message": "SQLSTATE[23000]: Integrity ..."
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     * )
     */    
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => "Bearer " . $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/user/update",
     *     tags={"Usuários"},
     *     summary="Alterando um usuário",
     *     description="Deve-se autenticar e pegar o token gerado. Clicar no cadeado e colocar Bearer mais o token gerado no login. Ex: Bearer 12|KvkT1313lSelWBH5xXxokLYOhN38B3XsS3riNuLKcca49988",
     *     security={{"sanctum":{}}}, 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 example={"id": 1, "name": "Rafael", "email": "rafael.frotac@gmail.com", "password": "teste"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User Updated Successfully",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="token",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "status": true,
     *                         "message": "User Created Successfully",
     *                         "token": "Bearer 8|KpIWQ5NCinZmPXszvViEJONX038iYaEQ4xzVT8hDe1b0d69e"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="401 Unauthorized",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     example={
     *                         "message": "Unauthenticated",
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     example={
     *                         "status": false,
     *                         "message": "User not found"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="500 Internal Server Error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     example={
     *                         "status": true,
     *                         "message": "SQLSTATE[23000]: Integrity ..."
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     */    
    public function updateUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'id' => 'required',
                'name' => 'required',
                'email' => 'required',
                'password' => 'required'
            ]);
            
            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = User::find($request->id);

            if(is_null($user)){
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 400);
            }

            $user = User::updateOrCreate(
                ['id' => $request->id],
                $request->all()
            );

            return response()->json([
                'status' => true,
                'message' => 'User Updated Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Usuários"},
     *     summary="Efetuando login",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "rafael.frotac@gmail.com", "password": "teste"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User Updated Successfully",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="token",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "status": true,
     *                         "message": "User Logged In Successfully",
     *                         "token": "Bearer 8|KpIWQ5NCinZmPXszvViEJONX038iYaEQ4xzVT8hDe1b0d69e"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Validation error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     example={
     *                         "status": false,
     *                         "message": "Email & Password does not match with our record."
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="500 Internal Server Error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     example={
     *                         "status": true,
     *                         "message": "SQLSTATE[23000]: Integrity ..."
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => "Bearer " . $user->createToken("API TOKEN")->plainTextToken
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
     *     path="/api/user",
     *     tags={"Usuários"},
     *     summary="Pegando usuário logado",
     *     description="Para o retorno dos dados, deve-se autenticar e pegar o token gerado. Clicar no cadeado e colocar Bearer mais o token gerado no login. Ex: Bearer 12|KvkT1313lSelWBH5xXxokLYOhN38B3XsS3riNuLKcca49988",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response="200",
     *         description="Get User Successfully",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="token",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "status": true,
     *                         "message": "User Logged In Successfully",
     *                         "data": {
     *                              "id": 5,
     *                              "name": "Rafael",
     *                              "email": "rafael.frotac@gmail.com",
     *                              "email_verified_at": null,
     *                              "created_at": "2024-04-16T23:58:14.000000Z",
     *                              "updated_at": "2024-04-17T01:42:09.000000Z"
     *                          }
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="401 Unauthorized",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     example={
     *                         "message": "Unauthenticated",
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="500 Internal Server Error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     example={
     *                         "status": false,
     *                         "message": "Throwable errors ..."
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function user(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json([
                'status' => true,
                'message' => 'Get User Successfully',
                'data' => $user,
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
     *     path="/api/users",
     *     tags={"Usuários"},
     *     summary="Pegando todos os usuários cadastrados",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response="200",
     *         description="Get User Successfully",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="token",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "status": true,
     *                         "message": "User Logged In Successfully",
     *                         "data": {{"id":5,"name":"Rafael","email":"rafael.frotac@gmail.com","email_verified_at":null,"created_at":"2024-04-16T23:58:14.000000Z","updated_at":"2024-04-17T01:42:09.000000Z"},{"id":6,"name":"Rafael","email":"rafael.frotac1@gmail.com","email_verified_at":null,"created_at":"2024-04-17T00:50:22.000000Z","updated_at":"2024-04-17T00:50:22.000000Z"},{"id":12,"name":"Rafael","email":"rafael.frotac2@gmail.com","email_verified_at":null,"created_at":"2024-04-17T01:30:38.000000Z","updated_at":"2024-04-17T01:30:38.000000Z"}}
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="401 Unauthorized",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     example={
     *                         "message": "Unauthenticated",
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="500 Internal Server Error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="bool",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     example={
     *                         "status": false,
     *                         "message": "Throwable errors ..."
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function users()
    {
        
        try {
            $users = User::all();
            return response()->json([
                'status' => true,
                'message' => 'Get Users Successfully',
                'data' => $users,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
