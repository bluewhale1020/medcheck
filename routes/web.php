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

Route::get('/', 'Auth\LoginController@showLoginForm');

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/test', 'TestController@import_service');
Route::get('/manual/{page}', 'DocumentController@manual');

Route::group(['prefix' => 'printer','middleware' => ['config_table']], function(){
    Route::get('/exportReceptionList', 'PrinterController@exportReceptionList');
    Route::get('/exportExamResult', 'PrinterController@exportExamResult');
    Route::get('/exportProgressChecklist', 'PrinterController@exportProgressChecklist');

});




// 以下のルートは他のルートより下に記述
//vue router routeのURL変更用
Route::get('{path}', 'HomeController@index')->where('path','([A-z\d-\/_.]+)?');