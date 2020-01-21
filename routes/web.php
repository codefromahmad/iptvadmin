<?php
//Route::get('/clear-cache', function() {
//    $exitCode = Artisan::call('config:clear');
//    $exitCode = Artisan::call('cache:clear');
//    $exitCode = Artisan::call('config:cache');
//    return 'DONE'; //Return anything
//});

Route::redirect('/', '/welcome');

Route::get('/welcome', 'WelcomeController');

Route::get('/iptv-register', 'IptvuserController@index')->name('iptv-register');

Route::post('iptv-store','IptvuserController@store')->name('iptv-store');

Route::post('admin/iptvuser/create','IptvuserController@store')->name('iptvuser.store');

Route::redirect('/home', '/admin');

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {


    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/iptvuser/create','HomeController@iptvcreate')->name('iptvuser.create');

    Route::get('/iptvuser/{id}','HomeController@showiptv')->name('iptvuser.show');

    Route::get('/iptvuser/{id}/edit','HomeController@editiptv')->name('iptvuser.edit');

    Route::post('/iptvuser/create','HomeController@storeiptv')->name('iptvuser.store');

    Route::post('/iptvuser/update','HomeController@updateiptv')->name('iptvuser.update');


    Route::get('/iptvuser','HomeController@iptvuser')->name('iptvuser.index');

    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');

    Route::resource('permissions', 'PermissionsController');

    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');

    Route::resource('roles', 'RolesController');

    Route::delete('iptvuser/delete/', 'HomeController@massDestroy')->name('iptvuser.massDestroy');

    Route::delete('iptvuser/destroy','HomeController@destroyiptv')->name('iptvuser.destroy');


    Route::delete('users/ddestroy', 'UsersController@massDestroy')->name('users.massDestroy');

    Route::resource('users', 'UsersController');

    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');

    Route::resource('products', 'ProductsController');
});






