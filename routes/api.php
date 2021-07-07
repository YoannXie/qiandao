<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QiandaoController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/setTops',[QiandaoController::class,'setTops']);
Route::get('/setUsers',[QiandaoController::class,'setUsers']);
Route::get('/cleanTable',[QiandaoController::class,'cleanTable']);

//Route::get('/getSigns',[QiandaoController::class,'getSigns']);
Route::get('/sign',[QiandaoController::class,'sign']);