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
Route::apiResources(['business_clearance'=>'API\BusinessClearanceController']);
Route::apiResources(['good_moral'=>'API\GoodMoralController']);
Route::apiResources(['system-info'=>'API\SystemController']);
Route::apiResources(['users'=>'API\UserController']);
Route::apiResources(['student_certificate'=>'API\StudentCertificateController']);
Route::apiResources(['barangay_indigency'=>'API\IndigencyCertificateController']);
Route::apiResources(['resident_certificate'=>'API\ResidentCertificateController']);
Route::apiResources(['blotter'=>'API\BlotterController']);
Route::apiResources(['crime_type'=>'API\CrimeController']);

//serach function
Route::get('search/blotter','API\BlotterController@saerchBlotter');
Route::get('search/residents','API\ResidentController@searchResidents');
Route::get('search/barangay','API\BarangayController@searchBarangay');
Route::get('search/officials','API\BarangayOfficialController@searchOfficials');
Route::get('search/business_clearance','API\BusinessClearanceController@searchClearance');
Route::get('search/good_moral','API\GoodMoralController@searchGoodmoral');
Route::get('search/student_certificate','API\StudentCertificateController@searchStudent');
Route::get('search/barangay_indigency','API\IndigencyCertificateController@searchIndifency');
Route::get('search/resident_certificate','API\ResidentCertificateController@searchResident');
//end search

Route::get('fetch/barangay_head','API\BarangayController@fetchBarangay');
Route::get('fetch/blotter/{id}','API\BlotterController@fetchBlotter');
Route::get('fetch/barangay','API\BarangayController@fetchBarangayAll');
Route::get('getStates','API\BarangayController@getStates');

Route::get('getBarangay','API\ResidentController@getBarangay');
Route::get('fetch/barangaySelected','API\ResidentController@barangaySelected');
Route::get('fetch/getbarangayinfo','API\DashboardController@getBarangayInfo');
Route::get('fetch/user_id','API\UserController@getUser');

Route::post('system-info/logo','API\SystemController@updateBarangayLogo');
Route::get('system/getlogo','API\SystemController@getBarangayLogo');
Route::get('system/logo/default','API\SystemController@setBarangayLogo');

Route::get('fetch/barangay/residents','API\BusinessClearanceController@fetchResidents');
Route::get('fetch/variable/path','API\SystemController@fetchVariable');
Route::get('fetch/barangay/officials','API\BarangayOfficialController@fetchOfficials');
Route::get('fetch/good_moral/data','API\GoodMoralController@fetchData');
Route::get('fetch/crime_type','API\CrimeController@fetchData');
// Route::get('getResidentBarangay','API\BarangayController@getResidentBarangay');