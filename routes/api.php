<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\JobController;

Route::group([
    'middleware' => ['assign.guard:api'],
    'namespace' => 'App\Http\Controllers',
    
], function(){
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('user', 'AuthController@profile');
    Route::post('refresh', 'AuthController@refresh');
});

//to test the new guard and middleware
Route::group([
    'middleware' => ['assign.guard:guest'],
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'guest'
], function(){
    //guest login
    Route::post('login', 'AuthController@guestLogin');
    //guest list all jobs
    Route::get('jobs', 'JobController@listAll');
    //guest gets a single job
    Route::get('jobs/{job_id}', 'JobController@show');
    //guest gets a single job and applys to it
    Route::get('jobs/{job_id}/apply', 'ApplicationController@apply');
    //guest search jobs

});

Route::group([
    'middleware' => ['assign.guard:api'],
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'my'
    
], function(){
    //business CRUD jobs
    Route::resource('jobs', 'JobController');
    //business sees all applications for one job_id
    Route::get('jobs/{job_id}/applications', 'ApplicationController@applications');
   
});


