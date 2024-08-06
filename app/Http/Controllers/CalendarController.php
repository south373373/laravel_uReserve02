<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 追記機能
use Carbon\CarbonImmutable;
// use Carbon\Carbon;
use App\Services\ConferenceService;

class CalendarController extends Controller
{   
    public function index()
    {
        // 現在の日付取得
        $currentDate = CarbonImmutable::today();

        // 1週間分の情報の配列
        $currentWeek = [];

        for ($i = 0; $i < 7; $i++) {
            // 日付の追加
            $day = CarbonImmutable::today()->addDays($i)->format('m月d日');
            array_push($currentWeek, $day);
        }
        // dd($currentDate, $currentWeek);

        // データをビューに渡す
        // return view('calendar');
        return view('calendar', compact('currentDate', 'currentWeek'));
    }

    public function getDate($date)
    {
        // 現在の日付取得
        $currentDate = $date;

        // 1週間分の情報の配列
        $currentWeek = [];

        for ($i = 0; $i < 7; $i++) {
            // parseでCarbonインスタンスに変換後、日付を加算
            $day = CarbonImmutable::parse($currentDate)->addDays($i)->format('m月d日');
            array_push($currentWeek, $day);
        }        
    }

    // public function getDate(Request $request)
    // {
    //     $currentDate = $request->input('date');
    //     $currentWeek = [];
    //     $sevenDaysLater = CarbonImmutable::parse($currentDate)->addDays(7);

    //     for ($i = 0; $i < 7; $i++) {
    //         $day = CarbonImmutable::parse($currentDate)->addDays($i)->format('m月d日');
    //         $checkDay = CarbonImmutable::parse($currentDate)->addDays($i)->format('Y-m-d');
    //         $dayOfWeek = CarbonImmutable::parse($currentDate)->dayName;
    //         $currentWeek[] = [
    //             'day' => $day,
    //             'checkDay' => $checkDay,
    //             'dayOfWeek' => $dayOfWeek,
    //         ];
    //     }

    //     return response()->json([
    //         'currentWeek' => $currentWeek,
    //         'conferences' => $conferences,
    //     ]);
    // }
}
