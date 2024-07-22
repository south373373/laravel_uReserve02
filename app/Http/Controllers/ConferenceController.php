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
    public function index()
    {
        //追記分
        // 本日の日付を取得
        $today = Carbon::today();
        
        // conferencesテーブルの昇順・表示件数設定
        // 本日以降の日付のみが一覧に表示
        $conferences = Conference::whereDate('start_date', '>=', $today)
        ->orderBy('start_date','asc')
        ->paginate(10);
        
        // resources > views > managerを作成
        return view('manager.conferences.index',compact('conferences'));
    }


    public function create()
    {
        //追記
        return view('manager.conferences.create');
    }


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
        session()->flash('status', '登録しました');

        return to_route('conferences.index');
    }


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


    public function edit(Conference $conference)
    {
        // 以下の$conferenceはindex側の情報を取得
        $conference = Conference::findOrFail($conference->id);

        $today = Carbon::today()->format('Y年m月d日');
        // 日付が当日より過去であれば「404」のエラー表示で編集不可
        if($conference->eventDate < $today)
        {
            return abort(404);
        }

        // Accessors と Mutatorsの設定を追記 
        // Models > Conferenceの別の表示形式に変更
        $eventDate = $conference->editEventDate;
        // $eventDate = $conference->eventDate;
        
        $startTime = $conference->startTime;
        $endTime = $conference->endTime;

        // compact内に上記の$eventDate・$startTime・$endTimeを追記
        return view('manager.conferences.edit', compact('conference','eventDate','startTime','endTime'));
    }


    public function update(UpdateConferenceRequest $request, Conference $conference)
    {
        // 重複のチェック詳細
        // - Services > ConferenceService.phpの関数を記載
        // 数量用のチェックに変更
        $check = ConferenceService::countEventDuplication($request['event_date'], $request['start_time'], $request['end_time']);
        // $check = ConferenceService::checkEventDuplication($request['event_date'], $request['start_time'], $request['end_time']);

        // dd($check);
        // 重複のチェック処理
        if($check > 1){
            // 以下の$conferenceはindex側の情報を取得
            $conference = Conference::findOrFail($conference->id);
            // Accessors と Mutatorsの設定を追記 
            // Models > Conferenceの別の表示形式に変更
            $eventDate = $conference->editEventDate;
            // $eventDate = $conference->eventDate;
            
            $startTime = $conference->startTime;
            $endTime = $conference->endTime;

            session()->flash('status', 'この時間帯は既に他の予約が存在します。');
            return view('manager.conferences.edit',compact('conference', 'eventDate', 'startTime', 'endTime'));
        }
        

        // 日付処理の機能
        // ・start_time用
        // - Services > ConferenceService.phpの関数を記載
        $startDate = ConferenceService::joinDateAndTime($request['event_date'], $request['start_time']);

        // ・end_time用
        // - Services > ConferenceService.phpの関数を記載
        $endDate = ConferenceService::joinDateAndTime($request['event_date'], $request['end_time']);

        // 既存情報を取得した上で上書きして保存
        $conference = Conference::findOrFail($conference->id);
        $conference->name = $request['event_name'];
        $conference->information = $request['information'];
        // start_date・end_dateは上記の変数を記載
        $conference->start_date = $startDate;
        $conference->end_date = $endDate;
        $conference->max_people = $request['max_people'];
        $conference->is_visible = $request['is_visible'];
        $conference->save();

        // flashメッセージを設定
        session()->flash('status', '更新しました');

        return to_route('conferences.index');
    }


    // 新規にpast(過去一覧用)のmethodを作成
    public function past()
    {
        // 本日の日付を取得
        $today = Carbon::today();

        $conferences = Conference::whereDate('start_date', '<', $today)
        ->orderBy('start_date','desc')
        ->paginate(10);
        // $conferences = DB::table('conferences')
        // ->whereDate('start_date', '<', $today)
        // ->orderBy('start_date','desc')
        // ->paginate(10);

        return view('manager.conferences.past', compact('conferences'));
    }

    public function destroy(Conference $conference)
    {
        //
    }

}
