<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\ReservationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {
    Route::apiResources([
        'meals' => MealController::class,
        'organizers' => OrganizerController::class,
        'beneficiaries' => BeneficiaryController::class,
        'reservations' => ReservationController::class,
    ]);
});
