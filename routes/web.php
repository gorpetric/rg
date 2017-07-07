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
    'prefix' => 'clanovi',
], function() {
    Route::get('', 'MembersController@index')->name('members.index');
    Route::get('novi', 'MembersController@getNewMember')->name('members.new');
    Route::post('novi', 'MembersController@postNewMember');
    Route::post('{member}/placanja', 'MembersController@postNewPayment');
});
