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

class ReservationController extends Controller
{
    public function dashboard()
    {
        $currentDate = CarbonImmutable::today();
        $currentWeek = $this->generateWeek($currentDate);
        $conferences = ConferenceService::getWeekConferences(
            $currentDate->format('Y-m-d'),
            $currentDate->addDays(7)->format('Y-m-d')
        );

        return view('dashboard', compact('currentDate', 'currentWeek', 'conferences'));
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
        return redirect()->route('manager.reservations.index');        
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
        return redirect()->route('manager.reservations.index');                

    }

    // 論理削除データの一覧
    public function trashed()
    {
        return view('manager.reservations.trashed');
    }

    // 論理削除データからの復旧処理
    public function restore($id)
    {
        $reservation = Reservation::withTrashed()->findOrFail($id);
        $reservation->restore();
        
        // flashメッセージを設定
        session()->flash('status', '予約管理へ戻しましたので、ご確認ください');
        return redirect()->route('manager.reservations.trashed');
    }

    // 物理削除の実施処理
    public function forceDestroy($id)
    {
        $reservation = Reservation::withTrashed()->findOrFail($id);
        $reservation->forceDelete();

        // flashメッセージを設定
        session()->flash('status', '完全に削除しました');
        return redirect()->route('manager.reservations.trashed');
    }
}
