<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Tymon\JWTAuth\Facades\JWTAuth;


class StaticPagesController extends Controller
{
	public function rootPage(Request $request) {

        // API jwt-auth でのログインユーザーのチェック
        if (Auth::guard('api')->check() == true) {
            return response()->json([
                'message' => 'successfly accessed.' . '',
                'data' => 'You are logged in.'
            ], 200, [], JSON_UNESCAPED_UNICODE);

		} else {
            return response()->json([
                'message' => 'successfly accessed.' . '',
                'data' => 'Welcome. Please login or register.'
            ], 200, [], JSON_UNESCAPED_UNICODE);
		}
	}
}
