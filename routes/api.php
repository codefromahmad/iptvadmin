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

Route::post('/m3ufiles','Admin/UserController@getList');
Route::post('/add','Admin/UserController@addm3u');
Route::get('/get/file/{path}','Admin/UserController@downloadfile')->name('getfile');
Route::post('/get','Admin/UserController@GetM3u');
Route::get('/test','Admin/UserController@test');
Route::post('/tester','Admin/UserController@get_all_user');
Route::post('/expires','Admin/UserController@UserExpirayDate');
