<?php

use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\RestaurantMenueController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\PrivacyPolicyController;
use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\TermsConditionController;
use App\Http\Controllers\Api\AddToCartController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\FavMenuController;
use App\Http\Controllers\Api\RiderChargesController;
use App\Http\Controllers\Api\PaymentCardInformationController;
use App\Http\Controllers\Api\GeneralSettingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RiderController;
use App\Http\Controllers\Api\UserReviewController;


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
Route::post('verify_otp', [RegisterController::class, 'verifyOtp']);
Route::post('change_password', [RegisterController::class, 'changePassword']);
Route::post('logout', [RegisterController::class, 'logoutUser']);
Route::get('verify-email/{token?}', [RegisterController::class, 'verifyUserEmail'])->name('email_verify');


Route::get('cuisine_list', [RestaurantController::class, 'get_cuisine_list']); 
Route::get('restaurant_menue/{id}', [RestaurantMenueController::class, 'edit']); 

Route::get('restaurant_list', [RestaurantController::class, 'index']);
Route::get('generalSetting', [GeneralSettingController::class, 'general_setting_index']); 

// Route::get('aboutus', [AboutController::class, 'index']); 
// Route::get('privacypolicy', [PrivacyPolicyController::class, 'index']); 
// Route::get('termscondition', [TermsConditionController::class, 'index']); 

Route::get('fav_menu', [FavMenuController::class, 'index']);


Route::middleware('auth:api')->group( function () {

	// Add to cart
	Route::post('/stripe_pament', [PaymentController::class, 'processPayment']);
	Route::post('/order_pickup/{id}', [PaymentController::class, 'order_pickup']);
	Route::get('/order_histroy', [PaymentController::class, 'index']);
	Route::get('/order_list', [PaymentController::class, 'order_list']);
	Route::post('/change_order_status/{id}', [PaymentController::class, 'change_order_status']);
	Route::post('add_cart/{id}', [AddToCartController::class, 'update']);
	Route::resource('add_cart', AddToCartController::class);

	// Cuisine
	Route::post('cuisine_store', [RestaurantController::class, 'cuisine_store']); 
	Route::post('cuisine_update/{id}', [RestaurantController::class, 'cuisine_update']); 
	Route::post('restaurant/{id}', [RestaurantController::class, 'update']); 
	Route::resource('restaurant', RestaurantController::class);
	
	// Schedule
	Route::get('schedule', [RiderController::class, 'schedule']); 
	Route::post('schedule_store', [RiderController::class, 'schedule_store']); 
	Route::post('schedule_update/{id}', [RiderController::class, 'schedule_update']); 
	Route::delete('destroy_schedule/{id}', [RiderController::class, 'destroy_schedule']); 
	
	// User Address
	Route::post('user_address', [RegisterController::class, 'user_address']); 
	Route::get('useraddress', [RegisterController::class, 'useraddress']); 
	Route::get('get_profile', [RegisterController::class, 'get_profile']); 
	Route::post('update_user_address/{id}', [RegisterController::class, 'update_user_address']); 
	Route::delete('delete_user_address/{id}', [RegisterController::class, 'delete_user_address']); 
	Route::post('editprofile', [RegisterController::class, 'edit_profile']);

	// Restaurant Menue
	Route::resource('restaurant_menue', RestaurantMenueController::class);
	Route::post('restaurant_menue/{id}', [RestaurantMenueController::class, 'update']);
	Route::post('required_menue_varients', [RestaurantMenueController::class, 'required_menue_varients_store']); 
	Route::post('optional_menue_varients', [RestaurantMenueController::class, 'optional_menue_varients_store']); 

	// Favourite Menu
	Route::resource('fav_menu', FavMenuController::class);
	Route::post('fav_menu/{id}', [FavMenuController::class, 'update']); 

	// User Reviews
	Route::resource('user_review', UserReviewController::class);

	// Payment Card Information 
	Route::resource('user_payment_cart_infomation', PaymentCardInformationController::class);
	Route::post('user_payment_cart_infomation/{id}', [PaymentCardInformationController::class, 'update']);
	
	//  Rider Charges
	Route::resource('rider_charge', RiderChargesController::class);
	
	Route::get('rider_list', [UserController::class,'index']);
	Route::post('change_rider_status/{id}', [UserController::class,'change_status']);
	Route::post('rider_charge/{id}', [RiderChargesController::class, 'update']);

	// Rider Vechicle Infomation
	Route::get('rider_earning',[RiderController::class, 'rider_earnings']);
	Route::resource('rider_vechicle', RiderController::class);

	// GeneralSetting
	Route::post('general_setting/{id}', [GeneralSettingController::class, 'update']);
	Route::resource('general_setting', GeneralSettingController::class);

});