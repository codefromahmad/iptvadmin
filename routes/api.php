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

Route::post('/m3ufiles','Admin\UsersController@getList');
Route::post('/add','Admin\UsersController@addm3u');
Route::get('/get/file/{path}','Admin\UsersController@downloadfile')->name('getfile');
Route::post('/get','Admin\UsersController@GetM3u');
Route::get('/test','Admin\UsersController@test');
Route::post('/tester','Admin\UsersController@get_all_user');
Route::post('/expires','Admin\UsersController@UserExpirayDate');
Route::post('/channel','Admin\UsersController@AboutChannels');
Route::post('/subscription','Admin\UsersController@IncreseExpiryDate');
