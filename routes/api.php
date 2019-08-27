<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('online_user', 'API\UserController@online_user');

Route::group(['middleware' => ['auth:api','config_table']], function(){
    Route::Resources(['user' => 'API\UserController']);
    Route::Resources(['role' => 'API\RoleController']);
    Route::Resources(['message_board' => 'API\MessageBoardController']);
     
    Route::get('stat', 'API\DashBoardController@statIndex');
    Route::get('event_list', 'API\EventListController@index');

    // Route::delete('reserve/{id}', 'API\ReserveInfoController@destroy');
    
    
    Route::Resources(['reserve' => 'API\ReserveInfoController']);
    Route::Resources(['manage_exam_area' => 'API\ExamAreaController']);
    Route::get('exam_area/columns', 'API\ExamAreaController@getColumns');
    Route::get('exam_area/image_path/{id}', 'API\ExamAreaController@getImagePath');
    
    Route::get('/side_menu_area/{id}', 'API\HomeController@getAreaList');    
    
    Route::post('import', 'API\ReceptionListController@import');
    Route::post('reception_list', 'API\ReceptionListController@create');
    
    Route::get('progress', 'API\ProgressManagementController@index');
    Route::get('progress/columns', 'API\ProgressManagementController@getColumns');
    Route::get('exam_result', 'API\ExamResultController@index');
    Route::get('exam_area/{id}/{mode}', 'API\ExamAreaController@getResultIndex');
    Route::put('exam_area/{reserve_info_id}', 'API\ExamAreaController@updateResult');
    // Route::get('select_item/columns', 'API\SelectItemController@getColumns');
    Route::get('exam_result/columns', 'API\ExamResultController@getColumns');
    Route::put('configuration', 'API\ConfigurationController@update');
    Route::get('configuration', 'API\ConfigurationController@index');
});
