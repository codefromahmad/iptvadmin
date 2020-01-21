<?php

//Route::group(['prefix' => 'v1', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin'], function () {
////    Route::apiResource('permissions', 'PermissionsApiController');
////
////    Route::apiResource('roles', 'RolesApiController');
////
////    Route::apiResource('users', 'UsersApiController');
////
////    Route::apiResource('products', 'ProductsApiController');
//});

Route::post('/m3ufiles','UserController@getList');
Route::post('/add','UserController@addm3u');
Route::get('/get/file/{path}','UserController@downloadfile')->name('getfile');
Route::post('/get','UserController@GetM3u');
Route::get('/test','UserController@test');
Route::post('/tester','UserController@get_all_user');
Route::post('/expires','UserController@UserExpirayDate');
