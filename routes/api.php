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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResources(['dashboard'=>'API\DashboardController']);
Route::apiResources(['barangay'=>'API\BarangayController']);
Route::apiResources(['residents'=>'API\ResidentController']);
Route::apiResources(['officials'=>'API\BarangayOfficialController']);
Route::apiResources(['users'=>'API\UserController']);

Route::get('fetch/barangay_head','API\BarangayController@fetchBarangay');
Route::get('fetch/barangay','API\BarangayController@fetchBarangayAll');
Route::get('getStates','API\BarangayController@getStates');

Route::get('getBarangay','API\ResidentController@getBarangay');
Route::get('fetch/barangaySelected','API\ResidentController@barangaySelected');
Route::get('fetch/getbarangayinfo','API\DashboardController@getBarangayInfo');
Route::get('fetch/user_id','API\UserController@getUser');
// Route::get('getResidentBarangay','API\BarangayController@getResidentBarangay');