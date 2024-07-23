<?php

namespace Database\Seeders;

use App\Models\Reservation;
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
        // dummy data
        Reservation::insert([
            [
                'user_id' => 1,
                'conference_id' => 1,
                'number_of_people' => 5,
                'canceled_date' => null
            ],
            [
                'user_id' => 2,
                'conference_id' => 1,
                'number_of_people' => 3,
                'canceled_date' => null
            ],
            [
                'user_id' => 1,
                'conference_id' => 2,
                'number_of_people' => 2,
                'canceled_date' => null
            ]            
        ]);
    }
}
