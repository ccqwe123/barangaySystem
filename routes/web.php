<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@login');
Auth::routes();
// Route::Resource('blotter', 'API\BlotterController');
Route::get('business_clearance/{id}/show','PrintController@bClearance');
// Route::resource('/templates','TemplateController');
Route::get('/template/business-clearance','TemplateController@index')->name('template.index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('{path}','HomeController@index')->where('path','([A-z\d\-\/_.]+)?');
