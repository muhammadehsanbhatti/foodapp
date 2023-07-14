<?php

use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\RestaurantMenueController;
use App\Http\Controllers\Api\RestaurantController;

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



Route::post('login', [RegisterController::class, 'login_user']);
Route::post('register', [RegisterController::class, 'register_user']);
Route::post('forgot_password', [RegisterController::class, 'forgotPassword']);
Route::post('change_password', [RegisterController::class, 'changePassword']);
Route::post('logout', [RegisterController::class, 'logoutUser']);
Route::get('verify-email/{token?}', [RegisterController::class, 'verifyUserEmail'])->name('email_verify');


Route::get('restaurant_list', [RestaurantController::class, 'index']); 
Route::get('cuisine_list', [RestaurantController::class, 'get_cuisine_list']); 
Route::get('restaurant_menue_list', [RestaurantMenueController::class, 'index']); 

Route::middleware('auth:api')->group( function () {
	
	Route::post('cuisine_store', [RestaurantController::class, 'cuisine_store']); 
	Route::post('cuisine_update/{id}', [RestaurantController::class, 'cuisine_update']); 
	Route::post('restaurant/{id}', [RestaurantController::class, 'update']); 
	Route::resource('restaurant', RestaurantController::class);
	
	Route::post('editprofile', [RegisterController::class, 'edit_profile']); 

	Route::post('restaurant_menue/{id}', [RestaurantMenueController::class, 'update']);
	Route::post('required_menue_varients', [RestaurantMenueController::class, 'required_menue_varients_store']); 

	Route::resource('restaurant_menue', RestaurantMenueController::class);
});