<?php

use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [FrontendController::class, 'welcome']);


Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('dashboard', [BackendController::class, 'dashboard'])->name('dashboard');
    Route::get('exchange-rate', [BackendController::class, 'exchangeRate'])->name('exchange_rate');
    Route::post('exchange-rate/store', [BackendController::class, 'exchangeRateStore'])->name('exchange_rate.store');
    Route::get('exchange-rate/{exchangeId}/destroy', [BackendController::class, 'exchangeRateDestroy'])->name('exchange_rate.destroy');
    Route::get('exchange-rate/{exchangeId}/edit', [BackendController::class, 'exchangeRateEdit'])->name('exchange_rate.edit');
    Route::post('exchange-rate/{exchangeId}/update', [BackendController::class, 'exchangeRateUpdate'])->name('exchange_rate.update');

    Route::get('content', [BackendController::class, 'content'])->name('content');
    Route::get('create-new-content', [BackendController::class, 'createNewContent'])->name('create_new_content');
    Route::post('create-new-content/store', [BackendController::class, 'contentStore'])->name('content.store');
    Route::post('content/{contentId}/destroy', [BackendController::class, 'contentDestroy'])->name('content.destroy');
    Route::post('content/{contentId}/update-duration', [BackendController::class, 'contentUpdateDuration'])->name('content.update_duration');

    Route::get('users', [BackendController::class, 'users'])->name('users');
    Route::get('user-create', [BackendController::class, 'userCreate'])->name('user.create');
    Route::post('user-store', [BackendController::class, 'userStore'])->name('user.store');


});

//authentication
Route::get('admin/login', [BackendController::class, 'login'])->name('login');
Route::post('admin/login/store', [BackendController::class, 'loginStore'])->name('login.store');
Route::post('logout', [BackendController::class, 'logout'])->name('logout');
