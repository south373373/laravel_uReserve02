<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\Reservation;
use Illuminate\Http\Request;
// 追加機能
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\MyPageService;
use Carbon\Carbon;

class MyPageController extends Controller
{
    //
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        // ユーザーに紐づく会議一覧を取得
        $conferences = $user->conferences;
        // 各ユーザー毎の本日を含む会議一覧を取得
        $fromTodayConferences = MyPageService::reservedConference($conferences, 'fromToday');
        // 各ユーザー毎の過去の会議一覧を取得
        $pastConferences = MyPageService::reservedConference($conferences, 'past');
        
        // データ取得確認
        // dd($user, $conferences, $fromTodayConferences, $pastConferences);

        return view('mypage/index', compact('fromTodayConferences', 'pastConferences'));
    }

    public function show($id)
    {
        // 会議情報の取得
        $conference = Conference::findOrFail($id);
        // 予約情報の取得
        $reservation = Reservation::where('user_id', '=', Auth::id())
            ->where('conference_id', '=', $id)
            ->latest() //最新の情報
            ->first();

        return view('mypage/show', compact('conference', 'reservation'));
    }

    // cancelボタンの処理の定義
    public function cancel($id)
    {
        // データ取得確認
        // dd($id);

        $reservation = Reservation::where('user_id', '=', Auth::id())
            ->where('conference_id', '=', $id)
            ->latest() //最新の情報
            ->first();
        
        $reservation->canceled_date = Carbon::now()->format('Y-m^d H:i:s');
        $reservation->save();

        // flashメッセージを設定
        return redirect()->route('dashboard')
            ->with('status', '予約を取り消しました');
    }
}
