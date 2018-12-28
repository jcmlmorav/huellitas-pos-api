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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::apiResource('products', 'API\ProductController');
Route::apiResource('billings', 'API\BillingController');
Route::get('billings-last', 'API\BillingController@last');
Route::get('sales', 'API\SalesController@index');
Route::get('sales-resume', 'API\SalesController@resume');
