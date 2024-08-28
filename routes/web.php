<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// 追記分
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\ReservationController;
use App\Models\Conference;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MyPageController;

// < blade用の記載 >
// resources > views配下のcalendar.blade.php直下のファイルを参照
// - 変数を定義しない場合は以下の記載。
// Route::get('/', function () {
//     return view('calendar');
// });

// - 変数を定義する場合は以下の記載。
// Route::get('/', [CalendarController::class, 'index'])->name('calendar.index');

// - データティッカーのテキストボックスにより日付選択から1週間分の日付を表示
Route::match(['get', 'post'], '/', [CalendarController::class, 'index'])->name('calendar.index');
// Route::get('/', [CalendarController::class, 'index'])->name('calendar.index');

// 今回は不要なため、コメント。
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// 通常の認証用
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 未ログイン時のカレンダー画面
// *未ログイン状態でカレンダー画面上にあるイベントの詳細画面からカレンダー画面へ戻るボタン用に設定。
//  序列的に認証ルーティングよりも上位に設定しないと「ログイン画面」へ誘導されるため。
Route::get('/guest/calendar', [CalendarController::class, 'index'])->name('guest.calendar');

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


    // Reservation用-論理削除・完全削除
    Route::get('reservations/trashed', [ReservationController::class, 'trashed'])->name('reservations.trashed');
    Route::delete('reservations/{reservation}/force', [ReservationController::class, 'forceDestroy'])->name('reservations.forceDestroy');
    // Reservation用-論理削除から復旧
    Route::patch('reservations/{reservation}/restore', [ReservationController::class, 'restore'])->name('reservations.restore');
    // Reservation用
    // Route::get('reservations/past', [ReservationController::class, 'past'])->name('reservations.past'); 
    Route::resource('reservations', ReservationController::class); 
});


// user側
Route::middleware(['auth', 'can:user-higher'])
->group(function(){
    // Route::match(['get', 'post'], '/', [CalendarController::class, 'index'])->name('index')->middleware('auth');
    Route::resource('reservations', ReservationController::class);
    Route::get('/dashboard', [ReservationController::class, 'dashboard'])->name('dashboard')->middleware('auth');
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/{id}', [MyPageController::class, 'show'])->name('mypage.show');
    Route::post('/mypage/{id}', [MyPageController::class, 'cancel'])->name('mypage.cancel');
    Route::get('/user/dashboard', [ReservationController::class, 'dashboard'])->name('user.dashboard');
    // Route::get('/conference/{id}', [ReservationController::class, 'detail'])->name('conferences.detail');
    Route::post('/conference/{id}', [ReservationController::class, 'reserve'])->name('conferences.reserve');
    // {id}の様な「{}」波カッコは可能な限り、下に記載した方が良いとの事。{}は何が入力されても良いため。
    // また同じアドレス指定の時に「get->post・・・」等の順番も配慮する事。php artisan route:listの表示順を参照。
});

// 未ログイン状態からのカレンダー上の会議の詳細表示用
Route::get('/conference/{id}', [ReservationController::class, 'detail'])->name('conferences.detail');

require __DIR__.'/auth.php';