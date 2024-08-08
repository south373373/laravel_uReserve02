<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 追記機能
use Carbon\CarbonImmutable;
// use Carbon\Carbon;
use App\Services\ConferenceService;
use App\Models\Conference;

class CalendarController extends Controller
{   
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            // 日付取得の処理
            $currentDate = CarbonImmutable::parse($request->input('date'));
            $currentWeek = [];

            for ($i = 0; $i < 7; $i++) {
                $day = $currentDate->addDays($i)->format('m月d日');
                $checkDay = $currentDate->addDays($i)->format('Y-m-d');
                $dayOfWeek = $currentDate->addDays($i)->dayName;
                array_push($currentWeek, [
                    'day' => $day,
                    'checkDay' => $checkDay,
                    'dayOfWeek' => $dayOfWeek,
                ]);
            }

            return response()->json([
                'currentWeek' => $currentWeek,
            ]);
        } else {
            // 最初にページを表示する処理
            $currentDate = CarbonImmutable::today();
            $currentWeek = [];

            for ($i = 0; $i < 7; $i++) {
                $day = CarbonImmutable::today()->addDays($i)->format('m月d日');
                $checkDay = CarbonImmutable::today()->addDays($i)->format('Y-m-d');
                $dayOfWeek = CarbonImmutable::today()->addDays($i)->dayName;
                array_push($currentWeek, [
                    'day' => $day,
                    'checkDay' => $checkDay,
                    'dayOfWeek' => $dayOfWeek,
                ]);
            }

            $conferences = Conference::where('start_date', '>=', $currentDate)
                                      ->where('start_date', '<=', $currentDate->addDays(30))
                                      ->get();

            return view('calendar', compact('currentDate', 'currentWeek', 'conferences'));
        }
    }

    // public function getDate($date)
    // {
    //     // 現在の日付取得
    //     $currentDate = $date;

    //     // 1週間分の情報の配列
    //     $currentWeek = [];

    //     for ($i = 0; $i < 7; $i++) {
    //         // parseでCarbonインスタンスに変換後、日付を加算
    //         $day = CarbonImmutable::parse($currentDate)->addDays($i)->format('m月d日');
    //         // 追記
    //         $checkDay = CarbonImmutable::parse($currentDate)->addDays($i)->format('Y-m-d');
    //         $dayOfWeek = CarbonImmutable::parse($currentDate)->dayName;
    //         $sevenDaysLater = CarbonImmutable::parse($currentDate)->addDays(7);

    //         // array_push($currentWeek, $day);
    //         array_push($currentWeek, [
    //             'day' => $day,
    //             'checkDay' => $checkDay,
    //             'dayOfWeek' => $dayOfWeek,
    //         ]);
    //     }
    //     // dd($currentWeek); 
        
    //     $conferences = Conference::where('start_date', '>=', $currentDate)
    //     ->where('start_date', '<=', $sevenDaysLater)
    //     ->get();

    //     return response()->json([
    //         'currentWeek' => $currentWeek,
    //     ]);
    // }
}
