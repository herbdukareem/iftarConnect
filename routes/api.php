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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/login', function() {
    return ["error"=>true,"status"=>400, "responseBody" => "You must be logged in to do that!"];
})->name('login');

Route::group(['prefix' => 'v1', 'as' => 'api'], function () {
      
    Route::get('/meals/{longitude?}/{latitude?}',[MealController::class, 'index']);    
    Route::get('/meals/{id}',[MealController::class, 'show']);
    
    Route::post('/logout',[OrganizerController::class, 'logout']); 

    Route::post('/organizers/login',[OrganizerController::class, 'login']); 
    Route::get('/organizers',[OrganizerController::class, 'index']);    
    Route::post('/organizers',[OrganizerController::class, 'store']);    
    Route::patch('/organizers',[OrganizerController::class, 'update']);    

    Route::group(['middleware'=>"auth:api:organizer"], function(){
        Route::get('/organizers/meals',[MealController::class, 'mealsByOwnerType']);
        Route::post('/meals',[MealController::class, 'store']);
        Route::patch('/meals',[MealController::class, 'update']);            
        Route::get('/organizers/reservations/{meal_id}',[ReservationController::class, 'index']);            
    });

    Route::get('/beneficiaries',[BeneficiaryController::class, 'index']);
    Route::get('/beneficiaries/{phone_number}',[BeneficiaryController::class, 'login']);

    Route::group(['middleware'=>"auth:api:beneficiary"], function(){
        Route::post('/beneficiaries',[BeneficiaryController::class, 'store']);
        
        Route::patch('/beneficiaries',[MealController::class, 'update']);            
        Route::post('/reservations',[ReservationController::class, 'store']);            
        Route::get('/reservations',[ReservationController::class, 'beneficiaryReservations']);            
    });

    /* Route::apiResources([                
        'reservations' => ReservationController::class,
    ]); */
});
