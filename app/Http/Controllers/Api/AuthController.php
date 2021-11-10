<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
//        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function register(Request $request): JsonResponse
    {
        $validate = $this->validator($request->all());

        if ($validate->fails()) {
            return new JsonResponse($validate->errors());
        }

        event(new Registered($user = $this->create($request->all())));

        return new JsonResponse($user);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(['message' => 'Successfully accessed.', 'data' => auth()->user()]);
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        try {
            return $this->respondWithToken(Auth::guard('api')->refresh());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'message' => 'Authorized',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }


}
