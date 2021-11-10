<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class StaticPagesController extends Controller
{
	public function rootPage(Request $request) {

        // ログイン状態に応じてTOP画面を切り替え
		if (Auth::check() === true) {
			return redirect()->route('estimates.index');
		} else {
            return view('welcome');
		}
	}
}
