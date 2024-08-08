<?php

namespace App\Services;

//上記のnamespaceより上にコード(コメント含む)を記載すると
// 「Namespace declaration statement has」のエラーが発生するので注意。

use Illuminate\Support\Facades\DB;
use App\Models\Conference;
use Carbon\Carbon;

class ConferenceService
{
    // 引数の変数名は任意
    // - 重複のチェック詳細用
    public static function checkEventDuplication($eventDate, $startTime, $endTime){
        // 重複のチェック詳細
        $check = Conference::whereDate('start_date', $eventDate)
            ->whereTime('end_date', '>', $startTime)
            ->whereTime('start_date', '<', $endTime)
            ->exists();
        return $check;    
    }

    // 引数の変数名は任意
    // - 重複のチェック詳細用 - 数量(重複しているイベントの数を確認)
    public static function countEventDuplication($eventDate, $startTime, $endTime){
        // 重複のチェック詳細
        $check = Conference::whereDate('start_date', $eventDate)
            ->whereTime('end_date', '>', $startTime)
            ->whereTime('start_date', '<', $endTime)
            ->count();
        return $check;    
    }

    // - 日付処理の機能用
    public static function joinDateAndTime($date, $time){
        $join = $date . " " . $time;
        $dateTime = Carbon::createFromFormat('Y-m-d H:i', $join);
        return $dateTime;
    }

    // 
    public static function getWeekConferences($startDate, $endDate)
    {
        return Conference::whereBetween('start_date', [$startDate, $endDate])
                    ->orderBy('start_date', 'asc')
                    ->get();
    }
}