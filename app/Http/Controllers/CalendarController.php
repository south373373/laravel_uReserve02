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
        // クエリパラメータから日付を取得。無い場合は本日の日付を取得。
        $currentDate = $request->query('date', CarbonImmutable::today()->format('Y-m-d'));
        $currentDate = CarbonImmutable::parse($currentDate);  // 日付をパース


        // 1週間分の情報を作成
        $currentWeek = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $currentDate->addDays($i)->format('m月d日');
            $checkDay = $currentDate->addDays($i)->format('Y-m-d');
            $dayOfWeek = $currentDate->addDays($i)->dayName;
            $currentWeek[] = [
                'day' => $day,
                'checkDay' => $checkDay,
                'dayOfWeek' => $dayOfWeek,
            ];
        }
    
        // 指定された日付範囲のイベントを取得
        $conferences = Conference::where('start_date', '>=', $currentDate)
                                  ->where('start_date', '<=', $currentDate->addDays(30))
                                  ->get();
    

        // リクエストがAJAXの場合、JSON形式でレスポンスを返す
        if ($request->ajax()) {
            return response()->json([
                'currentWeek' => $currentWeek,
                'conferences' => $conferences
            ]);
        }

        // 満員か否かの判定条件
        $conferenceData = [];
        foreach ($conferences as $conference) {
            $reservedPeople = $conference->reservations()->whereNull('canceled_date')->sum('number_of_people');
            $isFull = $conference->max_people <= $reservedPeople;
    
            $conferenceData[] = [
                'conference' => $conference,
                'isFull' => $isFull,
            ];
        }
    
        // 必要なデータをビューに渡す
        return view('calendar', compact('currentDate', 'currentWeek', 'conferenceData', 'conferences'));
    }

    private function generateWeek($currentDate)
    {
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
        return $currentWeek;
    }
}
