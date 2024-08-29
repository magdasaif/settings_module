<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

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

// Route::prefix('settings')->group(function() {
//     Route::get('/', 'SettingsController@index');
// });

Route::group(['prefix' => 'dashboard', 'as' => 'admin.', 'middleware' => ['auth', 'dashboard']], function () {
    // Route::name('settings.edit')->get('settings/edit', 'SettingsController@edit');
    // Route::name('settings.update')->patch('settings/edit', 'SettingsController@update');
    Route::resource('settings', SettingsController::class)->names('settings');
});
