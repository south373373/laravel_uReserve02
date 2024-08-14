<?php

namespace App\Services;

//上記のnamespaceより上にコード(コメント含む)を記載すると
// 「Namespace declaration statement has」のエラーが発生するので注意。

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MyPageService
{
    public static function reservedConference($conferences, $string)
    {
        // 事前に空の配列を準備
        $reservedConferences = [];
        
        // 今日以降の日付の場合
        if($string === 'fromToday')
        {
            foreach($conferences->sortBy('start_date') as $conference)
            {
                if(is_null($conference->pivot->canceled_date) && 
                $conference->start_date >= Carbon::now()->format('Y-m-d 00:00:00'))
                {
                    $conferenceInfo = [
                        'name' => $conference->name,
                        'start_date' => $conference->start_date,
                        'end_date' => $conference->end_date,
                        'number_of_people' => $conference->pivot->number_of_people
                    ];

                    array_push($reservedConferences, $conferenceInfo);
                }

            }
        }

        // 過去の日付の場合
        if($string === 'past')
        {
            foreach($conferences->sortByDesc('start_date') as $conference)
            {
                if(is_null($conference->pivot->canceled_date) && 
                // 過去のため「<」になるので注意。
                $conference->start_date < Carbon::now()->format('Y-m-d 00:00:00'))
                {
                    $conferenceInfo = [
                        'name' => $conference->name,
                        'start_date' => $conference->start_date,
                        'end_date' => $conference->end_date,
                        'number_of_people' => $conference->pivot->number_of_people
                    ];

                    array_push($reservedConferences, $conferenceInfo);
                }
            }
        }

        return $reservedConferences;
    }
}