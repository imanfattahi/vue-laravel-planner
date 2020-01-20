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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

# /api/v1/*
Route::group(['prefix' => 'v1'], function () {
    # Authenticated Users
    # /api/v1/account/*
    Route::group(['prefix' => 'account', 'middleware' => 'auth'], function () {
        # User Plans
        # /api/v1/account/planner/*
        Route::group(['prefix' => 'planner'], function () {
            # All user's plans
            Route::post('all', 'AccountController@getPlans');
            # Set (save or update) authenticated user plans'
            Route::post('set', 'AccountController@setPlan');
        });
    });
});
