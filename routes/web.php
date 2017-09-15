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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/login/{service}', 'SocialLoginController@redirect');
Route::get('/login/{service}/callback', 'SocialLoginController@callback');
Route::get('/logout', 'HomeController@logout')->name('logout')->middleware('auth');

Route::group([
    'middleware' => ['role:boss'],
    'prefix' => 'members',
], function() {
    Route::get('', 'MembersController@index')->name('members.index');
    Route::get('data', 'MembersController@getData');
    Route::post('{member}/payments', 'MembersController@postNewPayment');
    Route::get('{member}/edit', 'MembersController@getEditMember')->name('members.edit');
    Route::post('{member}/edit', 'MembersController@postEditMember');
    Route::get('new', 'MembersController@getNewMember')->name('members.new');
    Route::post('new', 'MembersController@postNewMember');
});

Route::group([
    'middleware' => ['role:admin'],
    'prefix' => 'admin',
], function() {
    Route::get('', 'AdminController@index')->name('admin.index');
    Route::post('/users/{user}/sync-roles', 'AdminController@syncUserRoles')->name('admin.users.syncRoles');

    Route::group(['prefix' => 'backup'], function() {
        Route::get('', 'BackupController@index')->name('admin.backup.index');
        Route::get('create', 'BackupController@create')->name('admin.backup.create');
        Route::get('{backup}/download', 'BackupController@download')->name('admin.backup.download');
        Route::get('{backup}/delete', 'BackupController@delete')->name('admin.backup.delete');
    });
});
