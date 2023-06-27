<?php

use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
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
Route::get('verify-email/{token?}', [RegisterController::class, 'verifyUserEmail'])->name('email_verify');








/*
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('forgot_password', [RegisterController::class, 'forgotPassword']);
*/

Route::middleware('auth:api')->group( function () {

    // Business Routes
    Route::resource('restaurant', RestaurantController::class);
    
    // Route::post('logout', [RegisterController::class, 'logoutProfile']);

    // Route::get('get_profile', [RegisterController::class, 'get_profile']);
    // Route::get('/connect_people_list', [RegisterController::class, 'connect_people_list']);
    // Route::post('/contact_user_list', [RegisterController::class, 'contact_user_list']);
    // Route::post('/connect_people', [RegisterController::class, 'connects_people']);
    // Route::post('/update_connect_people/{id}', [RegisterController::class, 'update_connects_people']);
    // Route::get('/education_information', [RegisterController::class, 'educational_info']);
    // Route::get('/degree_information', [RegisterController::class, 'degree_info']);
    // Route::post('/add_general_title', [RegisterController::class, 'create_general_title']);
    // Route::get('/general_titles', [RegisterController::class, 'general_titles']);
    // Route::get('/goals', [RegisterController::class, 'goals']);
    

    // // Messages
    // Route::post('/message_read', [MessageController::class, 'message_read']);
    // Route::post('/message_status', [MessageController::class, 'user_message_status']);
    // Route::get('/specific_general_title_list', [MessageController::class, 'specific_general_title']);
    // Route::resource('message', MessageController::class);
    
    // // Group
    // Route::post('/group_message', [GroupController::class, 'group_message']);
    // Route::post('group/{id}', [GroupController::class, 'update']); //->middleware('auth:api');//done
    // Route::post('destroy_group_member/{id}', [GroupController::class, 'destroy_group_member']); //->middleware('auth:api');//done
    // Route::resource('group', GroupController::class);
    
    // Route::post('update_profile', [RegisterController::class, 'updateProfile']);

    //resouce routes
    // Route::resource('services', ServiceController::class);
});