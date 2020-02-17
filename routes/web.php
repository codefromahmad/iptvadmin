<?php
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/news');
Route::get('/mylist','WelcomeController@mylist');
Route::get('/news','WelcomeController@news');


Route::get('/iptv-register', 'IptvuserController@index')->name('iptv-register');
Route::post('/destroy', 'IptvuserController@destroy')->name('iptv-delete');
Route::post('iptv-store','IptvuserController@store')->name('iptv-store');

Auth::routes(['register' => false]);
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@dashboard')->name('admin');
    Route::get('/home','HomeController@dashboard')->name('dashboard.home');
    Route::get('/news','HomeController@news')->name('news.index');
    Route::get('/news/create','HomeController@createnews')->name('news.create');
    Route::get('/news/store','HomeController@storenews')->name('news.store');
    Route::post('/news/update','HomeController@update_news')->name('news.update');
    Route::delete('/news/delete','HomeController@destroy_news')->name('news.destroy');
    Route::get('/news/{id}/edit','HomeController@editnews')->name('news.edit');
    Route::get('/news/{id}','HomeController@shownews')->name('news.show');


    Route::get('/iptvuser/create','HomeController@iptvcreate')->name('iptvuser.create');
    Route::delete('iptvuser/delete/', 'HomeController@massDestroy')->name('iptvuser.massDestroy');
    Route::get('/iptvuser/{id}','HomeController@showiptv')->name('iptvuser.show');
    Route::get('/iptvuser/{id}/edit','HomeController@editiptv')->name('iptvuser.edit');
    Route::post('/iptvuser/create','HomeController@storeiptv')->name('iptvuser.store');
    Route::post('/iptvuser/update','HomeController@updateiptv')->name('iptvuser.update');
    Route::get('/iptvuser','HomeController@iptvuser')->name('iptvuser.index');
    Route::delete('iptvuser/destroy','HomeController@destroyiptv')->name('iptvuser.destroy');

//    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
//    Route::resource('permissions', 'PermissionsController');
//    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
//    Route::resource('roles', 'RolesController');
//    Route::delete('users/ddestroy', 'UsersController@massDestroy')->name('users.massDestroy');
//    Route::resource('users', 'UsersController');
//    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');
//    Route::resource('products', 'ProductsController');
});






