<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\WebsiteController\alertController;
use App\Http\Controllers\WebsiteController\dataController;
use App\Http\Controllers\WebsiteController\emailController;
use App\Http\Controllers\WebsiteController\organizationController;
use App\Http\Controllers\WebsiteController\permissionController;
use App\Http\Controllers\WebsiteController\roleController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::get('/mail', [emailController::class, 'sendTestEmail']);
// Route::get('/mq2', [agentController::class, 'consume']);



Route::group(['middleware' => ['auth:api']], function () {



    Route::get('/getsession', [sessionController::class, 'sessiondetail'])->middleware('checkPermission:sessiondetail');
    // Route::get('/sessionnames', [sessionController::class, 'sessionnames'])->middleware('checkPermission:sessionnames');
    Route::get('/sessionnames', [sessionController::class, 'sessionnames']);

    Route::get('/getmetrics', [sessionController::class, 'sessionmetrics'])->middleware('checkPermission:sessionmetrics');
    Route::get('/analytics', [analyticController::class, 'index'])->middleware('checkPermission:view_analytics');
    Route::get('/get-ref-sessions', [agentController::class, 'referenceSessions'])->middleware('checkPermission:referenceSessions');
    Route::get('/organizations', [organizationController::class, 'index'])->middleware('checkPermission:view_organizations');
    Route::post('/add-organization', [organizationController::class, 'store'])->middleware('checkPermission:add_organization');
    Route::post('/assign-organization', [organizationController::class, 'assign'])->middleware('checkPermission:assign_organization');
    Route::post('/edit-organization', [organizationController::class, 'edit'])->middleware('checkPermission:edit_organization');
    Route::post('/delete-organization', [organizationController::class, 'delete'])->middleware('checkPermission:delete_organization');
    Route::get('/roles', [roleController::class, 'index'])->middleware('checkPermission:view_roles');
    Route::post('/role-details', [roleController::class, 'roleDetails'])->middleware('checkPermission:view_role_details');
    Route::post('/assign-role', [roleController::class, 'assignRole'])->middleware('checkPermission:assign_role');
    Route::post('/add-role', [roleController::class, 'store'])->middleware('checkPermission:add_role');
    Route::post('/edit-role', [roleController::class, 'edit'])->middleware('checkPermission:edit_role');
    Route::post('/delete-role', [roleController::class, 'delete'])->middleware('checkPermission:delete_role');
    Route::get('/permissions', [permissionController::class, 'index'])->middleware('checkPermission:view_permissions');
    Route::post('/assign-permissions', [permissionController::class, 'assign'])->middleware('checkPermission:assign_permissions');
    Route::post('/remove-permissions', [permissionController::class, 'remove'])->middleware('checkPermission:remove_permissions');
    Route::post('/register', [AuthController::class, 'register'])->middleware('checkPermission:register');
    Route::get('/groups', [groupControlller::class, 'index'])->middleware('checkPermission:view_groups');
    Route::get('/agents', [agentController::class, 'index'])->middleware('checkPermission:view_agents');
    Route::get('/cluster', [homeController::class, 'getCluster'])->middleware('checkPermission:get_cluster');
    Route::get('/alerts', [alertController::class, 'index'])->middleware('checkPermission:view_alerts');
    Route::get('/links', [homeController::class, 'getLinks'])->middleware('checkPermission:view_links');
    Route::get('/agentlinks', [homeController::class, 'agentLinks'])->middleware('checkPermission:view_agentlinks');
    Route::get('/sessions', [sessionController::class, 'index'])->middleware('checkPermission:view_sessions');
    Route::get('/profiles', [profileController::class, 'index'])->middleware('checkPermission:view_profiles');
    Route::post('add-agent', [agentController::class, 'add_agent'])->middleware('checkPermission:add_agent');
    Route::post('edit-agent', [agentController::class, 'edit_agent'])->middleware('checkPermission:edit_agent');
    Route::post('add-session', [sessionController::class, 'store'])->middleware('checkPermission:add_session');
    Route::post('add-profile', [profileController::class, 'store'])->middleware('checkPermission:add_profile');
    Route::post('add-alert', [alertController::class, 'store'])->middleware('checkPermission:add_alert');
    Route::post('delete-agent', [agentController::class, 'delete'])->middleware('checkPermission:delete_agent');
    Route::post('delete-session', [sessionController::class, 'delete'])->middleware('checkPermission:delete_session');
    Route::post('delete-profile', [profileController::class, 'delete'])->middleware('checkPermission:delete_profile');
    Route::post('send-report', [sessionController::class, 'report'])->middleware('checkPermission:send_report');
    Route::post('register_agent', [agentController::class, 'register'])->middleware('checkPermission:register_agent');
    Route::post('add-group', [groupControlller::class, 'store'])->middleware('checkPermission:add_group');
    Route::post('delete-group', [groupControlller::class, 'remove'])->middleware('checkPermission:delete_group');
    Route::post('edit-group', [groupControlller::class, 'edit'])->middleware('checkPermission:edit_group');
    Route::get('get-group/{id}', [groupControlller::class, 'getdata'])->middleware('checkPermission:get_group');
 




});