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

Route::get('privacy', 'HomeController@privacy')->name('privacy');

Route::get('/login/{service}', 'SocialLoginController@redirect');
Route::get('/login/{service}/callback', 'SocialLoginController@callback');
Route::get('/logout', 'HomeController@logout')->name('logout')->middleware('auth');

Route::delete('/impersonate', 'Admin\ImpersonateController@destroy')->name('admin.users.impersonate.stop');

Route::group([
    'middleware' => ['role:boss'],
    'prefix' => 'members',
], function() {
    Route::get('', 'Members\MembersController@index')->name('members.index');
    Route::get('data', 'Members\MembersController@getData');
    Route::post('{member}/payments', 'Members\MemberPaymentsController@postNewPayment');
    Route::delete('{member}/payments/{payment}', 'Members\MemberPaymentsController@deletePayment');
    Route::post('{member}/edit', 'Members\MembersController@postEditMember');
    Route::post('new', 'Members\MembersController@postNewMember');
    Route::get('stats/monthly', 'Members\MemberPaymentsController@getMonthlyStats');

    Route::get('{member}/vacuum', 'Members\MemberVacuumController@index');
    Route::post('{member}/vacuum', 'Members\MemberVacuumController@createGroup');
    Route::post('{member}/vacuum/{group}', 'Members\MemberVacuumController@createAppointment');
    Route::post('{member}/vacuum/{group}/{appointment}', 'Members\MemberVacuumController@createMeasurement');
    Route::post('{member}/vacuum/{group}/{appointment}/delete-measure', 'Members\MemberVacuumController@deleteMeasurement');
    Route::post('{member}/vacuum/{group}/{appointment}/complete-appointment', 'Members\MemberVacuumController@completeAppointment');
    Route::post('{member}/vacuum/{group}/{appointment}/edit-appointment', 'Members\MemberVacuumController@editAppointment');
});

Route::group([
    'middleware' => ['role:admin'],
    'prefix' => 'admin',
], function() {
    Route::get('', 'Admin\AdminController@index')->name('admin.index');
    Route::post('/users/{user}/sync-roles', 'Admin\AdminController@syncUserRoles')->name('admin.users.syncRoles');

	Route::get('/impersonate/{user}', 'Admin\ImpersonateController@store')->name('admin.users.impersonate');

    Route::group(['prefix' => 'backup'], function() {
        Route::get('', 'Admin\BackupController@index')->name('admin.backup.index');
        Route::get('create', 'Admin\BackupController@create')->name('admin.backup.create');
        Route::get('{backup}/download', 'Admin\BackupController@download')->name('admin.backup.download');
        Route::get('{backup}/delete', 'Admin\BackupController@delete')->name('admin.backup.delete');
    });

    Route::get('logs', 'Admin\AdminController@getLogs')->name('admin.logs');
});
