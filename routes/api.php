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
Route::post('/expires','Admin\UsersController@UserExpirayDate');
Route::post('/channel','Admin\UsersController@AboutChannels');
Route::post('/subscription','Admin\UsersController@IncreseExpiryDate');
