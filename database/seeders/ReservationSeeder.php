<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// 追記分
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //dummy data
        // DB::table('reservations')->insert([
        //     [
        //         'user_id' => 1,
        //         'event_id' => 1,
        //         'number_of_people' => 5,
        //         'canceled_date' => null
        //     ],
        //     [
        //         'user_id' => 2,
        //         'event_id' => 1,
        //         'number_of_people' => 5,
        //         'canceled_date' => null
        //     ]
        // ]);
    }
}
