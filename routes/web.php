<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EmailShortCodeController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AssignPermissionController;
use App\Http\Controllers\SubMenuController;
use App\Http\Controllers\SubCategorieController;
use App\Http\Controllers\ImportExcelFileController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Artisan;

//
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

Route::get('/clear-cache', function() {
    // Artisan::call('optimize');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('config:cache');
    return '<h1>Cache facade value cleared</h1>';
})->name('clear-cache');

// Route::get('/schedule-run', function() {
//     Artisan::call("schedule:run");
//     return '<h1>schedule run activated</h1>';
// });

// Route::get('/site-down', function() {
//     Artisan::call('down --secret="harrypotter"');
//     return '<h1>Application is now in maintenance mode.</h1>';
// });

// Route::get('/site-up', function() {
//     Artisan::call('up');
//     return '<h1>Application is now live..</h1>';
// });

// Route::get('/run-seeder', function() {
//     Artisan::call("db:seed");
//     return '<h1>Dummy data added successfully</h1>';
// });

Route::get('/storage-link', function() {
    Artisan::call("storage:link");
    return '<h1>storage link activated</h1>';
});
    
// Route::get('/queue-work', function() {
//     Artisan::call("queue:work");
//     return '<h1>queue work activated</h1>';
// });
    
// Route::get('/migration-refresh', function() {
//     Artisan::call('migrate:refresh');
//     return '<h1>Migration refresh successfully</h1>';
// });
    
// Route::get('/migration-fresh', function() {
//     Artisan::call("migrate:fresh");
//     return '<h1>Migration fresh successfully</h1>';
// });
    
// Route::get('/passport-install', function() {   
//     Artisan::call('passport:install');
//     return '<h1>Passport install successfully</h1>';
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/socket', function () {
    return view('eventForm');
});
Route::get('/send_socket', function () {
    event (new \App\Events\NewTrade('Event trigger!'));
    echo'<pre>'; print_r('Event send successfully'); echo'</pre>';exit;
});

// Route::get('/', function () {
//     redirect()->route('clear-cache');
//     return view('auth_v1.login');
// })->name('welcome');

// Route::get('/sp-login', function () {
//     redirect()->route('clear-cache');
//     return view('auth_v1.login');
// })->name('sp-login');

// Route::get('/test', [UserController::class, 'testing']);
// // Route::get('/', [UserController::class, 'welcome']);
// // Route::get('/sp-login', [UserController::class, 'login'])->name('sp-login');
// Route::get('/logout', [UserController::class, 'logout']);
// Route::get('/send_notification', [UserController::class, 'sendNotification']);
// // ->name('logout');

// Route::get('/register', [UserController::class, 'register'])->name('register');
// Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('forgotPassword');
// Route::get('/reset-password', [UserController::class, 'resetPassword'])->name('resetPassword');

// Route::post('/accountRegister', [UserController::class, 'accountRegister'])->name('accountRegister');
// Route::post('/accountLogin', [UserController::class, 'accountLogin'])->name('accountLogin');
// Route::post('/resetPassword', [UserController::class, 'accountResetPassword'])->name('accountResetPassword');

// Route::get('contact-us', [ContactController::class, 'index']);
// Route::post('contact-us', [ContactController::class, 'store'])->name('contact.us.store');

// Route::middleware(['auth'])->group(function () {
     
//     Route::post('/blockUnblockUser', [UserController::class, 'blockUnblockUser']);
//     Route::post('/theme_mode', [UserController::class, 'theme_layout']);
//     Route::post('/notification_token', [NotificationController::class, 'get_notificatiion_token']);
//     Route::post('/import_data_submit', [ImportExcelFileController::class, 'csv_import_data'])->name('csv_import_data');

//     Route::get('/dashboard', [UserController::class, 'dashboard']);
//     Route::get('/editProfile', [UserController::class, 'editProfile']);
//     Route::get('contact-us-list', [ContactController::class, 'contactUsList']);
//     Route::post('export', [UserController::class, 'export_data'])->name('export_data_to_file');
//     Route::post('/delete_goal_item/{id}', [GoalController::class, 'destroy_goal_item']);

//     //resouce routes 
//     Route::resource('role', RoleController::class);
//     Route::resource('user', UserController::class);
//     Route::resource('category', CategorieController::class);
//     Route::resource('sub_category', SubCategorieController::class);
//     Route::resource('menu', MenuController::class);
//     Route::resource('permission', PermissionController::class);
//     Route::resource('assign_permission', AssignPermissionController::class);
//     Route::resource('sub_menu', SubMenuController::class);
//     Route::resource('notification', NotificationController::class);
//     Route::resource('email_template', EmailTemplateController::class);
//     Route::resource('short_codes', EmailShortCodeController::class);
//     Route::resource('import_excel', ImportExcelFileController::class);
//     Route::resource('general_title', GeneraltitleController::class);
//     Route::resource('goal', GoalController::class);
//     Route::resource('industry_vertical_item', IndustryVerticalItemController::class);
//     Route::resource('professional_role_type', ProfessionalRoleTypeController::class);
// });