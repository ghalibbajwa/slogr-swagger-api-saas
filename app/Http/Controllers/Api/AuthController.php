<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Auth;
use App\User;
use App\Role;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     description="Registers a new user in the system",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "email", "password", "password_confirmation"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The name of the user"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="The email address of the user"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="The password of the user"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string",
     *                     format="password",
     *                     description="The confirmation of the password"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="The authentication token"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The name of the user"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Tag(name="Authentication"),
     *     @OA\Header(
     *         header="Accept",
     *         description="Accept header",
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     )
     * )
     */

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $success['token'] = $user->createToken('authToken')->accessToken;
        $success['name'] = $user->name;
        return response()->json(['success' => $success]);
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     description="Logs in a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="The email address of the user"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="The password of the user"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="The authentication token"
     *                 ),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     description="The authenticated user's information",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         description="The user's ID"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="The user's name"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         format="email",
     *                         description="The user's email address"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorised - Invalid credentials"
     *     ),
     *     @OA\Tag(name="Authentication"),
     *     @OA\Header(
     *         header="Accept",
     *         description="Accept header",
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        if (!auth()->guard('web')->attempt($validator)) {
            return response()->json(['error' => 'Unauthorised'], 401);
        } else {
            // dd(auth()->guard('web')->user());
            $success['token'] = auth()->guard('web')->user()->createToken('authToken')->accessToken;
            $success['user'] = auth()->guard('web')->user();
            return response()->json(['success' => $success])->setStatusCode(200);
        }
    }


       /**
     * @OA\Post(
     *     path="/api/assign",
     *     summary="Assign Role",
     *     description="Assigns role to a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User and Role",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "role id"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="The email address of the user"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="integer",
     *                     
     *                     description="role id {admin : 1, guest : 2, moderator : 3}"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role added",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="The authentication token"
     *                 ),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     description="The authenticated user's information",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         description="The user's ID"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="The user's name"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         format="email",
     *                         description="The user's email address"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorised - Invalid credentials"
     *     ),
     *     @OA\Tag(name="Authentication"),
     *     @OA\Header(
     *         header="Accept",
     *         description="Accept header",
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     )
     * )
     */
    public function assign(Request $request){
        // dd($request->role);
        
        $user = User::where('email','=',$request->email)->first();
        $role = Role::find($request->role);

        $user->roles()->attach($role);

        return response()->json(['success' => [$user,$role]])->setStatusCode(200);

    }
}