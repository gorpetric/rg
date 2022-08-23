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

//Route::get('/login/{service}', 'SocialLoginController@redirect');
//Route::get('/login/{service}/callback', 'SocialLoginController@callback');
Route::get('login', 'AuthController@index')->name('login')->middleware('guest');
Route::post('login', 'AuthController@login')->name('login')->middleware('guest');
Route::get('logout', 'AuthController@logout')->name('logout')->middleware('auth');

Route::group([
    'middleware' => ['auth'],
], function() {
    Route::get('auth/change-password', 'AuthController@changePassword')->name('auth.changepassword');
    Route::post('auth/change-password', 'AuthController@postChangePassword')->name('auth.changepassword');
});

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

    Route::group([
        'prefix' => 'vacuum'
    ], function() {
        Route::get('', 'Members\MemberVacuumController@index')->name('members.vacuum.index');
        Route::post('', 'Members\MemberVacuumController@createMember');
        Route::get('{member}', 'Members\MemberVacuumController@member');
        Route::post('{member}', 'Members\MemberVacuumController@createGroup');
        Route::post('{member}/{group}', 'Members\MemberVacuumController@createAppointment');
        Route::post('{member}/{group}/{appointment}', 'Members\MemberVacuumController@createMeasurement');
        Route::post('{member}/{group}/{appointment}/delete-measure', 'Members\MemberVacuumController@deleteMeasurement');
        Route::post('{member}/{group}/{appointment}/complete-appointment', 'Members\MemberVacuumController@completeAppointment');
        Route::post('{member}/{group}/{appointment}/edit-appointment', 'Members\MemberVacuumController@editAppointment');
    });

    Route::group([
        'prefix' => 'stats'
    ], function() {
        Route::get('', 'Members\MembersStatsController@index')->name('members.stats.index');
        Route::get('get-stats', 'Members\MembersStatsController@getStats');
    });
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

    Route::group(['prefix' => 'settings'], function() {
        Route::get('', 'Admin\SettingsController@index')->name('admin.settings.index');
        Route::post('', 'Admin\SettingsController@postNewSetting');
        Route::post('{setting}', 'Admin\SettingsController@postEditSetting');
        Route::delete('{setting}/delete', 'Admin\SettingsController@deleteSetting');
    });
});
