<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConferenceRequest;
use App\Http\Requests\UpdateConferenceRequest;
use App\Models\Conference;
// 追記分
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\ConferenceService;

class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //追記分
        // conferencesテーブルの昇順・表示件数設定
        $conferences = Conference::orderBy('start_date','asc')->paginate(10);
        
        // resources > views > managerを作成
        return view('manager.conferences.index',compact('conferences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //追記
        return view('manager.conferences.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConferenceRequest $request)
    {
        //追記分

        // 重複のチェック
        // $check = DB::table('conferences')
        // ->whereDate('start_date', $request['event_date'])
        // ->whereTime('end_date', '>', $request['start_time'])
        // ->whereTime('start_date', '<', $request['end_time'])
        // ->exists();

        // 重複のチェック詳細
        // - Services > ConferenceService.phpの関数を記載
        $check = ConferenceService::checkEventDuplication($request['event_date'], $request['start_time'], $request['end_time']);

        // dd($check);
        // 重複のチェック処理
        if($check){
            session()->flash('status', 'この時間帯は既に他の予約が存在します。');
            return view('manager.conferences.create');
        }
        

        // 日付処理の機能
        // ・start_time用
        // - Services > ConferenceService.phpの関数を記載
        $startDate = ConferenceService::joinDateAndTime($request['event_date'], $request['start_time']);

        // ・end_time用
        // - Services > ConferenceService.phpの関数を記載
        $endDate = ConferenceService::joinDateAndTime($request['event_date'], $request['end_time']);


        Conference::create([
            'name' => $request['event_name'],
            'information' => $request['information'],
            // start_date・end_dateは上記の変数を記載
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_people' => $request['max_people'],
            'is_visible' => $request['is_visible'], 
        ]);

        // flashメッセージを設定
        session()->flash('status', '登録OKです');

        return to_route('conferences.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Conference $conference)
    {
        // 以下の$conferenceはindex側の情報を取得
        $conference = Conference::findOrFail($conference->id);
        // Accessors と Mutatorsの設定を追記 
        $eventDate = $conference->eventDate;
        $startTime = $conference->startTime;
        $endTime = $conference->endTime;

        // DB取得の確認
        // dd($eventDate, $startTime, $endTime);

        // compact内に上記の$eventDate・$startTime・$endTimeを追記
        return view('manager.conferences.show', compact('conference','eventDate','startTime','endTime'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conference $conference)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConferenceRequest $request, Conference $conference)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conference $conference)
    {
        //
    }
}
