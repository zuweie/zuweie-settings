<?php
use Zuweie\Setting\Http\Controllers\SettingController;

Route::get('setting', SettingController::class.'@index');
Route::get('settingdata', SettingController::class.'@settingdata');
Route::post('update/setting', SettingController::class.'@updateSetting');
Route::post('create/setting', SettingController::class.'@createSetting');
Route::delete('delete/settings', SettingController::class.'@deleteSettings');
Route::get('settings/tags', SettingController::class.'@getTags');
Route::get('debug/cache', SettingController::class.'@debugCache');
