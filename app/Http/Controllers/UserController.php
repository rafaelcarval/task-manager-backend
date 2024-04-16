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
     *         response=200,
     *         description="User Created Successfully",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": true, "message": "User Created Successfully", "token": "1|cnThSUDvuQ0doEz0bhNmTtWKWFsu7MSESPQw2XaP74a03a98"}, summary="An result object."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation request",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":false,"message":"validation error","errors":{"email":{"The email has already been taken."}}}, summary="An result object."),
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
     *         response=200,
     *         description="User Updated Successfully",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": true, "message": "User Updated Successfully", "token": "1|cnThSUDvuQ0doEz0bhNmTtWKWFsu7MSESPQw2XaP74a03a98"}, summary="An result object."),
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
     *         response=200,
     *         description="User Logged In Successfully",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": true, "message": "User Logged In Successfully", "token": "1|cnThSUDvuQ0doEz0bhNmTtWKWFsu7MSESPQw2XaP74a03a98"}, summary="An result object."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": false, "message": "Email & Password does not match with our record."}, summary="An result object."),
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
     * @OA\Get(
     *     path="/api/user",
     *     tags={"Usuários"},
     *     summary="Pegando usuário logado",
     *     description="Para o retorno dos dados, deve-se autenticar e pegar o token gerado. Clicar no cadeado e colocar Bearer mais o token gerado no login. Ex: Bearer 12|KvkT1313lSelWBH5xXxokLYOhN38B3XsS3riNuLKcca49988",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User Logged In Successfully",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"id":1,"name":"Rafael","email":"rafael.frotac@gmail.com","email_verified_at":null,"created_at":"2024-04-14T17:00:48.000000Z","updated_at":"2024-04-14T17:00:48.000000Z"}, summary="An result object."),
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
    public function user(Request $request)
    {
        return $request->user();
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Usuários"},
     *     summary="Pegando todos os usuários cadastrados",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Get all successfully",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"id":1,"name":"Rafael","email":"rafael.frotac@gmail.com","email_verified_at":null,"created_at":"2024-04-14T17:00:48.000000Z","updated_at":"2024-04-14T17:00:48.000000Z"}, summary="An result object."),
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
    public function users()
    {
        return $user = User::all();
    }
}
