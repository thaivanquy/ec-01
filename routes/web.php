<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\LocationController;
use App\Http\Controllers\Backend\SlugController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'backend', 'as' => 'backend.', 'middleware' => ['web', 'throttle:120,1']], function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',[AuthController::class,'formLogin'])->name('formLogin');
        Route::post('/login',[AuthController::class,'processLogin'])->name('processLogin');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/logout',[AuthController::class,'logout'])->name('logout');
    
        Route::group([
            'prefix' => '/users',
            'as' => 'users.'
        ], function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/detail/{userInfo}', [UserController::class, 'detail'])->name('detail');
            Route::get('/edit/{userInfo}', [UserController::class, 'edit'])->name('edit');
            Route::post('/update/{userInfo}', [UserController::class, 'update'])->name('update');
            Route::post('/delete/{userInfo}', [UserController::class, 'delete'])->name('delete');
            Route::post('/change-publish', [UserController::class, 'changePublish'])->name('changePublish');
        });

        Route::group([
            'prefix' => '/categories',
            'as' => 'categories.'
        ], function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/store', [CategoryController::class, 'store'])->name('store');
            Route::get('/detail/{categoryInfo}', [CategoryController::class, 'detail'])->name('detail');
            Route::get('/edit/{categoryInfo}', [CategoryController::class, 'edit'])->name('edit');
            Route::post('/update/{categoryInfo}', [CategoryController::class, 'update'])->name('update');
            Route::post('/delete/{categoryInfo}', [CategoryController::class, 'delete'])->name('delete');
        });
    
        Route::get('location', [LocationController::class, 'index'])->name('location');
        Route::get('slug', [SlugController::class, 'index'])->name('slug');
        
    });

});
