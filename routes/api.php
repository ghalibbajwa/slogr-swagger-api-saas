<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\WebsiteController\alertController;
use App\Http\Controllers\WebsiteController\dataController;
use Illuminate\Http\Request;

use App\Http\Controllers\schedular\schedularController;
use App\Http\Controllers\WebsiteController\agentController;
use App\Http\Controllers\WebsiteController\analyticController;
use App\Http\Controllers\WebsiteController\groupControlller;
use App\Http\Controllers\WebsiteController\homeController;
use App\Http\Controllers\WebsiteController\profileController;
use App\Http\Controllers\WebsiteController\sessionController;
use Illuminate\Support\Facades\Route;



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



// dd('sds');
// dd(Auth::user()->roles->pluck('name')->toArray()[0]);

Route::group(['middleware' => 'web'], function () {
    Route::get('api/documentation', '\L5Swagger\Http\Controllers\SwaggerController@api')->name('l5swagger.api');

});


Route::post('/login', 'Api\AuthController@login');
Route::get('/mq', [agentController::class, 'mqtest']);
Route::get('/mq2', [agentController::class, 'consume']);



Route::group(['middleware' => ['auth:api']] ,function () {
  
     
    //    dd(Auth::user());
        // if (Auth::user()->roles->pluck('name')->toArray()[0] == "admin") {

            Route::post('/register', [AuthController::class, 'register']);
            Route::post('assign', 'Api\AuthController@assign');


        // } 
        // if (Auth::user()->roles->pluck('name')->toArray()[0] == "guest") {
            
        // }
       
        Route::get('/groups', [groupControlller::class, 'index']);
        Route::get('/agents', [agentController::class, 'index']);
        Route::get('/cluster', [homeController::class, 'getCluster']);
        Route::get('/alerts', [alertController::class, 'index']);
        Route::get('/data', [dataController::class, 'index']);

        // Route::get('/down/{id}',  [agentController::class, 'getDownload']);
        Route::get('/sessions', [sessionController::class, 'index']);
        Route::get('/profiles', [profileController::class, 'index']);
        Route::get('/push/{id}', [profileController::class, 'push']);
        Route::get('/ip', [agentController::class, 'get_ip']);
        Route::get('/analytics/{session}', [analyticController::class, 'index']);
        Route::get('/map', [homeController::class, 'index']);
        Route::get('/agentdata/{id}/{profile}', [analyticController::class, 'agentdata']);
        Route::get('/agentlogs/{id}', [analyticController::class, 'agentlogs']);



        Route::get('/getip', function () {
            return response(url(''));
        });
        Route::post('add-agent', [agentController::class, 'add_agent']);
        Route::post('edit-agent', [agentController::class, 'edit_agent']);
        Route::post('add-session', [sessionController::class, 'store']);
        Route::post('add-profile', [profileController::class, 'store']);
        Route::post('add-alert', [alertController::class, 'store']);



        Route::post('delete-agent', [agentController::class, 'delete']);
        Route::post('delete-session', [sessionController::class, 'delete']);
        Route::post('delete-profile', [profileController::class, 'delete']);


        Route::post('send-report', [sessionController::class, 'report']);
        Route::post('register_agent', [agentController::class, 'register']);


        Route::post('add-group', [groupControlller::class, 'store']);
        Route::post('get-group', [groupControlller::class, 'getdata']);

        Route::get('/sch', [schedularController::class, 'schedule']);


   
    
});

