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
use App\Models\Conference;


class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    // 追記分
    // Conference::create()で保存のために追記
    protected $fillable = [
        'user_id',
        'conference_id',
        'number_of_people',
    ];

    // Userとのリレーション(多対1)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Conferenceとのリレーション(多対1)
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

}
