<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// 追加分
use App\Models\Conference;
use App\Models\Reservation;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Conference用ダミーデータのfactoryを挿入
        Conference::factory(100)->create();

        // UserSeeder用のダミーデータを挿入
        $this->call([
            UserSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}
