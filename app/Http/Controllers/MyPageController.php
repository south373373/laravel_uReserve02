<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 追加機能
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\MyPageService;

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

        return view('mypage/index', compact('fromTodayConferences', 'pastConfereces'));
    }
}
