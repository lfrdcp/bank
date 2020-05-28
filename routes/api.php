<?php

use App\BaseDinamica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::get('posts', 'PostsController@index');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    Auth::attempt(['email' => request('email'), 'password' => request('password')]);
    return $request->user();
});
