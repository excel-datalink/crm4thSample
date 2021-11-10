<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use App\Http\Requests\StoreProfileRequest;


class ProfilesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
	 **/
	public function edit($id)
	{
//		$profile = Profile::find($id);

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

		return redirect()
		        ->route('profiles.edit', ['profile' => $id])
		        ->withInput()
		        ->with('message', __('Updated.'));
	}

}
