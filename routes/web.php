<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// 追記分
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\ReservationController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 管理責任者側
Route::prefix('manager')
->middleware('can:manager-higher')
->group(function(){
    // テスト確認
    // Route::get('index', function(){
    //     dd('manager');
    // });

    // Conference用
    Route::resource('conferences', ConferenceController::class); 
});

// user側
Route::middleware('can:user-higher')
->group(function(){
    Route::get('index', function(){
        dd('user');
    });
});

require __DIR__.'/auth.php';
