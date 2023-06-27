<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AssignPermissionController;
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
/waqas test
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



