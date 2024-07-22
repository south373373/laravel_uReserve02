<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// 追記分
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\ReservationController;
use App\Models\Conference;

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


    // [注意事項] 以下のRouteのパスで「trashed」を上位にしないと、「conferences/{conference}」のパスが
    //           先に読み込まれると、上手くパスが読み込めないので注意。
    // 
    // Conference用-論理削除・完全削除
    Route::get('conferences/trashed', [ConferenceController::class, 'trashed'])->name('conferences.trashed');
    Route::delete('conferences/{conference}/force', [ConferenceController::class, 'forceDestroy'])->name('conferences.forceDestroy');
    // Conferece用-論理削除から復旧
    Route::patch('conferences/{conference}/restore', [ConferenceController::class, 'restore'])->name('conferences.restore');
    // Conference用
    Route::get('conferences/past', [ConferenceController::class, 'past'])->name('conferences.past'); 
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
