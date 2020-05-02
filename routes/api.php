<?php

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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('moods/today', 'MoodsController@today');
    Route::resource('moods', 'MoodsController');
    Route::get('constants', 'ConstantsController@getConstants');
    Route::get('garments/outfit', 'GarmentsController@getOutfit');
    Route::get('garments/wear', 'GarmentsController@wear');
    Route::resource('garments', 'GarmentsController');
    Route::resource('spending_categories', 'SpendingCategoriesController');
    Route::resource('litems', 'LitemsController');
    Route::resource('comments', 'CommentsController');
    Route::resource('camera', 'CamerasController');
    Route::get('expenses/totals', 'ExpensesController@getTotals');
    Route::resource('expenses', 'ExpensesController');
    Route::get('temperature/current', 'TemperatureController@getCurrent');
    Route::resource('temperature', 'TemperatureController');
    Route::get('users', 'UsersController@index');
    Route::get('users/me', 'UsersController@currentUserInfo');
    Route::post('users/firebase', 'UsersController@updateFirebaseToken');
    Route::resource('heater', 'HeaterController');
    Route::resource('places_', 'PlacesController');
    Route::get('heater/turn_on', 'HeaterController@turnOn');
    Route::get('heater/turn_off', 'HeaterController@turnOff');
    Route::get('heater/get_logs', 'HeaterController@viewLogs');
    Route::get('check_air', 'HeaterController@checkAir');
});
