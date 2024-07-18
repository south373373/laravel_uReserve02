<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConferenceRequest;
use App\Http\Requests\UpdateConferenceRequest;
use App\Models\Conference;
// 追記分
use Illuminate\Support\Facades\DB;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConferenceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Conference $conference)
    {
        //
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
