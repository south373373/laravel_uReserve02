<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 追記機能
// use Carbon\CarbonImmutable;
use Carbon\Carbon;
use App\Services\ConferenceService;

class CalendarController extends Controller
{    
    public function index()
    {
        // 現在の日付取得
        $currentDate = Carbon::today();

        // 1週間分の情報の配列
        $currentWeek = [];

        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::today()->addDays($i)->format('m月d日');
            array_push($currentWeek, $day);
        }
        // dd($currentWeek);

        // データをビューに渡す
        return view('calendar.index', compact('currentDate', 'currentWeek'));
    }
}
