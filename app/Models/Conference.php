<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 日付関連の機能追記
use Carbon\Carbon;
// Accessors と Mutatorsの機能追記
use Illuminate\Database\Eloquent\Casts\Attribute;
// 削除用の機能追記
use Illuminate\Database\Eloquent\SoftDeletes;
// 他Modelsの追記
use App\Models\User;


class Conference extends Model
{
    // softDeletesを追記
    use HasFactory, SoftDeletes;

    // 追記分
    // Conference::create()で保存のために追記
    protected $fillable = [
        'name',
        'information',
        'max_people',
        'start_date',
        'end_date',
        'is_visible',
    ];

    // Accessors と Mutatorsの実装
    protected function eventDate(): Attribute
    {
       return Attribute::make(
            get: fn () => Carbon::parse($this->start_date)->format('Y年m月d日'),
       ); 
    }

    // 上記の eventDateとは表示形式を変更
    protected function editEventDate(): Attribute
    {
       return Attribute::make(
            get: fn () => Carbon::parse($this->start_date)->format('Y-m-d'),
       ); 
    }

    protected function startTime(): Attribute
    {
       return Attribute::make(
            get: fn () => Carbon::parse($this->start_date)->format('H時i分'),
       ); 
    }

    protected function endTIme(): Attribute
    {
       return Attribute::make(
            get: fn () => Carbon::parse($this->end_date)->format('H時i分'),
       ); 
    }

    // Userモデルとのリレーション設定(1対)
    public function users()
    {
        return $this->belongsToMany(User::class, 'reservations')
        ->withPivot('id', 'number_of_people', 'canceled_date');
    }

    // 指摘箇所の追記分
    // public function reservations()
    // {
    //     return $this->hasMany(Reservation::class)->whereNull('canceled_date');
    // }

    // protected function scopeAfterToday($query)
    // {
    //     return $query
    //         ->whereIsVisible(0)
    //         ->whereDate('start_date', '>=', Carbon::now())
    //         ->OrderBy('start_date', 'asc')
    //         ->paginate();
    // }

}
