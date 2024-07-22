<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 日付関連の機能追記
use Carbon\Carbon;
// Accessors と Mutatorsの機能追記
use Illuminate\Database\Eloquent\Casts\Attribute;


class Conference extends Model
{
    use HasFactory;

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
}
