<?php

use Illuminate\Http\Request;
use App\models\UrgentReport;
use App\models\Complain;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('login', 'api\UserController@login')->middleware('cors');
Route::get('register', 'api\UserController@get_register')->middleware('cors');
Route::post('register', 'api\UserController@register')->middleware('cors');
Route::group(['middleware' => 'auth:api'], function() {
    // User
    Route::resource('Users', 'api\UserController')->middleware('cors');
    Route::post('details', 'api\UserController@details')->middleware('cors');
    Route::get('ChangePassword', 'api\UserController@getChangePassword')->middleware('cors');
    Route::post('ChangePassword', 'api\UserController@postChangePassword')->middleware('cors');
    // Urgent Reports
    // Route::resource('UrgentReports', 'api\UrgentReportController')->middleware('cors','can:urgent_reports.create');
    Route::group(['prefix' => 'UrgentReports'], function(){
        Route::get('/', 'api\UrgentReportController@index')
        ->name('list_urgent_report')->middleware('cors','can:urgent_reports.view');
        Route::get('/{id}', 'api\UrgentReportController@show')
        ->name('show_urgent_report')->middleware('cors');
        Route::get('/create', 'api\UrgentReportController@create')
        ->name('create_urgent_report')->middleware('cors','can:urgent_reports.create');
        Route::post('/create', 'api\UrgentReportController@store')
        ->name('store_urgent_report')->middleware('cors');
    });
    // serious problem types
    Route::resource('SeriousProblemTypes', 'api\SeriousProblemTypes')->middleware('cors');
    Route::resource('Hospitals', 'api\HospitalsController')->middleware('cors');
    // Depts
    Route::resource('Depts', 'api\DeptController')->middleware('cors');
    // Department
    Route::resource('Departments', 'api\DepartmentsController')->middleware('cors');
     // Complain
    // Route::resource('Complains', 'api\ComplainController')->middleware('cors','can:urgent_reports.create');
    Route::group(['prefix' => 'Complains'], function(){
        Route::get('/', 'api\ComplainController@index')
        ->name('index_complains')->middleware('cors','can:complains.view');
        Route::get('/{id}', 'api\ComplainController@show')
        ->name('show_complains')->middleware('cors');
        Route::get('/create', 'api\ComplainController@create')
        ->name('create_complains')->middleware('cors','can:complains.create');
        Route::post('/create', 'api\ComplainController@store')
        ->name('store_complains')->middleware('cors','can:complains.create');
    });
    // Assaulted
    Route::group(['prefix' => 'AssaultedStaff'], function(){
        Route::get('/', 'api\AssaultedStaffController@index')
        ->name('index_assaulted_staff')->middleware('cors');
        Route::get('/{id}', 'api\AssaultedStaffController@show')
        ->name('show_assaulted_staff')->middleware('cors');
        Route::get('/create', 'api\AssaultedStaffController@create')
        ->name('create_assaulted_staff')->middleware('cors');
        Route::post('/create', 'api\AssaultedStaffController@store')
        ->name('store_assaulted_staff')->middleware('cors');
    });
      // Labor Accident
    Route::group(['prefix' => 'LaborAccident'], function(){
        Route::get('/', 'api\LaborAccidentController@index')
        ->name('index_labor_accident')->middleware('cors');
        Route::get('/{id}', 'api\LaborAccidentController@show')
        ->name('show_labor_accident')->middleware('cors');
        Route::get('/create', 'api\LaborAccidentController@create')
        ->name('create_labor_accident')->middleware('cors');
        Route::post('/create', 'api\LaborAccidentController@store')
        ->name('store_labor_accident')->middleware('cors');
    });
       // Damages Natural Disaster
    Route::group(['prefix' => 'DamagesDisaster'], function(){
        Route::get('/', 'api\DamagesDisasterController@index')
        ->name('index_damages_disaster')->middleware('cors');
        Route::get('/{id}', 'api\DamagesDisasterController@show')
        ->name('show_damages_disaster')->middleware('cors');
        Route::get('/create', 'api\DamagesDisasterController@create')
        ->name('create_damages_disaster')->middleware('cors');
        Route::post('/create', 'api\DamagesDisasterController@store')
        ->name('store_damages_disaster')->middleware('cors');
    });
    // Position
    Route::resource('Positions', 'api\PositionController')->middleware('cors');    
    // Account Types
    Route::resource('AccountTypes', 'api\AccountTypeController')->middleware('cors');

    //Trend reports
    Route::group(['prefix' => 'TrendReports'], function(){
        Route::get('/', 'api\TrendReportController@index')
            ->name('index_trend_report')->middleware('cors');
    });
});

