<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group(["middleware" => "api"], function () {
    // 認証が必要ないメソッド
    Route::get('/', 'Api\StaticPagesController@rootPage')->name('api.top');
    Route::post('/register', 'Api\AuthController@register');
    Route::post('/login',    'Api\AuthController@login');
    Route::get('/refresh',   'Api\AuthController@refresh');

    Route::group(['middleware' => ['jwt.auth']], function () {
        // 認証が必要なメソッド
        Route::resource('/customers', 'Api\CustomersController',  ['except' => ['create', 'edit']]);
        Route::resource('/products' , 'Api\ProductsController',   ['except' => ['create', 'edit']]);
        Route::resource('/estimates', 'Api\EstimatesController',  ['except' => ['create', 'edit']]);
        Route::resource('/profiles' , 'Api\ProfilesController',   ['except' => ['store', 'destroy', 'show', 'create', 'edit']]);
        Route::post('/logout', 'Api\AuthController@logout');
        Route::get('/me', 'Api\AuthController@me');
    });
});
