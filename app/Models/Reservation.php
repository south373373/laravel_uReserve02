<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // 追記分
    // Conference::create()で保存のために追記
    protected $fillable = [
        'user_id',
        'conference_id',
        'number_of_people',
    ];


}
