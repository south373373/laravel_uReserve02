<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
// 追記分
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use App\Services\ConferenceService;
use App\Models\Conference;
use Illuminate\Support\Facades\Auth;

// $reservedPeopleが空か、最大定員 >= 予約人数 + 入力された人数 なら予約可能


class ReservationController extends Controller
{
    // dashboard上のカレンダー上の日付選択実行時の処理
    // 引数にデータを受け取るRequestを追記
    public function dashboard(Request $request)
    {
        // $currentDate = CarbonImmutable::today();
        // 以下の通りにインスタンス化
        $currentDate = new CarbonImmutable($request->date);
        $currentWeek = $this->generateWeek($currentDate);
        $conferences = ConferenceService::getWeekConferences(
            $currentDate->format('Y-m-d'),
            $currentDate->addDays(7)->format('Y-m-d')
        );

        $conferenceData = [];
        
        // 「満員」フラグを以下の通りに定義
        $isFull = 0;

        foreach ($conferences as $conference) {
            $reservedPeople = Reservation::where('conference_id', $conference->id)
                ->whereNull('canceled_date')
                ->sum('number_of_people');
    
            $isFull = $conference->max_people <= $reservedPeople;
    
            $conferenceData[] = [
                'day' => CarbonImmutable::parse($conference->start_date)->format('Y-m-d'),
                'name' => $conference->name,
                'isFull' => $isFull,
            ];
        }

        return view('dashboard', compact('currentDate', 'currentWeek', 'conferences','isFull'));
    }

    private function generateWeek($currentDate)
    {
        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $currentDate->addDays($i)->format('m月d日');
            $checkDay = $currentDate->addDays($i)->format('Y-m-d');
            $dayOfWeek = $currentDate->addDays($i)->dayName;
            $week[] = [
                'day' => $day,
                'checkDay' => $checkDay,
                'dayOfWeek' => $dayOfWeek,
            ];
        }
        return $week;
    }

    // 
    public function detail($id)
    {
        $conference = Conference::findOrFail($id);

        $currentDate = CarbonImmutable::today();
        $currentWeek = $this->generateWeek($currentDate);

        // // 予約数の合計queryの処理
        // $reservedPeople = Reservation::select('conference_id', Reservation::raw('sum(number_of_people) as number_of_people'))
        // // cancelの場合、合計人数から外す。
        // ->whereNull('canceled_date')
        // ->groupBy('conference_id')
        // // 更に表示されているイベント情報と指定
        // ->having('event_id', $conference->id)
        // ->first();

        // 現在の予約人数の合計を取得
        $reservedPeople = Reservation::where('conference_id', $conference->id)
        ->whereNull('canceled_date')
        ->sum('number_of_people');

        // // 予約の有無で判定
        // if(!is_null($reservedPeople))
        // {
        //     // 予約可能な人数は、定員人数(max_people)から予約人数(number_of_people)を引いた値。
        //     $reservablePeople = $conference->max_people - $reservedPeople->number_of_people;
        // }
        // else{
        //     $reservablePeople = $conference->max_people;
        // }

        // ログイン対象ユーザーがイベントの予約済みか否かの絞り込み
        $isReserved = Reservation::where('user_id', '=', Auth::id())
            ->where('conference_id', '=', $id)
            ->where('canceled_date', '=', null)
            ->latest()
            ->first();

        // 予約可能人数を計算
        $reservablePeople = $conference->max_people - $reservedPeople;

        // 満員かどうかのフラグ
        $isFull = $reservablePeople <= 0;

        // return view('conference-detail', compact('conference', 'reservablePeople'));
        return view('conference-detail', compact('conference', 'currentDate', 'currentWeek', 'reservablePeople', 'isFull', 'isReserved'));
    }

    // 排他ロックを使用せずに、予約人数の超過を防止。
    // 但し、これは完璧な解決策ではなく非常に高い同時アクセスがある場合には依然として競合の可能性有り。
    public function reserve(Request $request)
    {
        $conference = Conference::findOrFail($request->id);

        // 予約数の合計queryの処理
        $reservedPeople = Reservation::select('conference_id', Reservation::raw('sum(number_of_people) as number_of_people'))
            // 以下を追記。
            ->where('conference_id', $conference->id)
            // cancelの場合、合計人数から外す。
            ->whereNull('canceled_date')
            ->groupBy('conference_id')
            // 更に表示されているイベント情報と指定
            // ->having('event_id', $conference->id)
            ->having('conference_id', $conference->id)
            ->first();        

        if(is_null($reservedPeople) || 
            $conference->max_people >= $reservedPeople->number_of_people + $request->reserved_people)
        {
            // ここでもう一度データベースを確認
            $currentReservedPeople = Reservation::select('conference_id', DB::raw('sum(number_of_people) as number_of_people'))
                ->where('conference_id', $conference->id)
                ->whereNull('canceled_date')
                ->groupBy('conference_id')
                ->first();
            
            if (is_null($currentReservedPeople) || 
            $conference->max_people >= $currentReservedPeople->number_of_people + $request->reserved_people)
            {
                Reservation::create([
                    'user_id' => Auth::id(),
                    'conference_id' => $conference->id,
                    'number_of_people' => $request->reserved_people,
                ]);
    
                // flashメッセージを設定
                session()->flash('status', '登録しました');
                return redirect()->route('dashboard');
            }
            else{
                session()->flash('status', 'この人数では予約が出来ません。');
                // 対象のイベントでの予約画面上でエラー時に「dashboard」画面へ遷移。
                return redirect()->route('dashboard', $conference->id);
                // 対象のイベントでの予約画面上でエラーであれば以下。
                // return redirect()->route('conferences.detail', $conference->id);
            }
        }
        else{
            session()->flash('status', 'この人数では予約が出来ません。');
            // 対象のイベントでの予約画面上でエラー時に「dashboard」画面へ遷移。
            return redirect()->route('dashboard', $conference->id);
            // 対象のイベントでの予約画面上でエラーであれば以下。
            // return redirect()->route('conferences.detail', $conference->id);
        }
    }

    // << 代替の修正案 >>
    // 
    // public function reserve(Request $request)
    // {
    //     $conference = Conference::findOrFail($request->id);
    
    //     // 予約人数の合計を計算
    //     $reservedPeople = Reservation::where('conference_id', $conference->id)
    //         ->whereNull('canceled_date')
    //         ->sum('number_of_people'); // `sum`メソッドを使用
    
    //     // 予約可能かどうかの判定
    //     if ($conference->max_people >= $reservedPeople + $request->reserved_people) {
    //         // ここでもう一度データベースを確認
    //         $currentReservedPeople = Reservation::where('conference_id', $conference->id)
    //             ->whereNull('canceled_date')
    //             ->sum('number_of_people');
    
    //         if ($conference->max_people >= $currentReservedPeople + $request->reserved_people) {
    //             Reservation::create([
    //                 'user_id' => Auth::id(),
    //                 'conference_id' => $conference->id,
    //                 'number_of_people' => $request->reserved_people,
    //             ]);
    
    //             // flashメッセージを設定
    //             return redirect()
    //                 ->route('dashboard')
    //                 ->with('status', '登録しました');
    //         } else {
    //             return redirect()
    //                 ->route('dashboard', $conference->id)
    //                 ->with('status', 'この人数では予約が出来ません。');
    //         }
    //     } else {
    //         return redirect()
    //             ->route('dashboard', $conference->id)
    //             ->with('status', 'この人数では予約が出来ません。');
    //     }
    // }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //全ての予約情報を取得
        $reservations = Reservation::with(['user', 'conference'])->paginate(10);

        return view('manager.reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('manager.reservations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        // バリデーションの実施
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'conference_id' => 'required|exists:conferences,id',
            'number_of_people' => 'required|integer|min:1',
        ]);

        // 予約の作成
        Reservation::create($validated);

        // flashメッセージを設定
        session()->flash('status', '予約が作成されました');
        // return redirect()->route('manager.reservations.index');        
        return redirect()->route('reservations.index');        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // IDに基づいて予約を取得
        $reservation = Reservation::with(['user','conference'])->findOrFail($id);

        // 詳細を表示
        return view('manager.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // IDに基づいて予約を取得
        $reservation = Reservation::with(['user','conference'])->findOrFail($id);

        // 詳細を表示
        return view('manager.reservations.edit', compact('reservation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //バリデーションの実施
        $validated = $request->validate([
            'number_of_people' => 'required|integer|min:1',
        ]);

        // 予約の取得と更新
        $reservation = Reservation::findOrFail($id);
        $reservation->update($validated);

        // flashメッセージを設定
        session()->flash('status', '予約人数が更新されました');
        return redirect()->route('reservations.index', $id);                

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
        $reservation = Reservation::findOrFail($reservation->id);
        $reservation->delete();

        // flashメッセージを設定
        session()->flash('status', '予約が削除されました');
        // return redirect()->route('manager.reservations.index');                
        return redirect()->route('reservations.index');                

    }

    // < 今回の予約一覧については「物理削除」のみ実装 >
    // 
    // // 論理削除データの一覧
    // public function trashed()
    // {
    //     return view('manager.reservations.trashed');
    // }

    // // 論理削除データからの復旧処理
    // public function restore($id)
    // {
    //     $reservation = Reservation::withTrashed()->findOrFail($id);
    //     $reservation->restore();
        
    //     // flashメッセージを設定
    //     session()->flash('status', '予約管理へ戻しましたので、ご確認ください');
    //     return redirect()->route('manager.reservations.trashed');
    // }

    // 物理削除の実施処理
    public function forceDestroy(Reservation $reservation)
    {
        // $reservation = Reservation::withTrashed()->findOrFail($id);
        $reservation->forceDelete();

        // flashメッセージを設定
        // session()->flash('status', '予約を完全に削除しました');
        // return redirect()->route('reservations.index');

        return redirect()
            ->route('reservations.index')
            ->with('status', '予約を完全に削除しました');
    }
}
