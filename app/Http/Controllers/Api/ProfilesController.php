<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Profile;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class ProfilesController extends Controller
{
	public function __construct()
	{
        // API jwt-auth でのログインユーザーのチェック
        if (Auth::guard('api')->check() == true) {
            $this->middleware('auth:api', ['except' => ['login']]);
        } else {
            $this->middleware('auth');
        }

//        $this->middleware(function ($request, $next) {
//            $accept_language = $request->header('Accept-Language');
//            // 言語の切り替え
//            App::setLocale($accept_language);
//
//            return $next($request);
//        });
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Profile の GlobalScope で 'user_id' をログインユーザーid でフィルター済み
        $profile = Profile::first();
        $profile_count = $profile->count();

        return response()->json([
            'message' => __('Displaying :counts item',['counts' => $profile_count]),
            'data' => $profile
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
	 **/
	public function edit($id)
	{
        // Profile の GlobalScope で 'user_id' をログインユーザーid でフィルター済み
        $profile = Profile::first();

		return view('profiles.edit', compact('profile'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProfileRequest $request, $id)
	{
		$profile = Profile::first();
		$profile->fill($request->all());
		$profile->save();

        return response()->json([
            'message' => __('Updated.'),
            'data' => $profile
        ], 200, [], JSON_UNESCAPED_UNICODE);
	}

}
