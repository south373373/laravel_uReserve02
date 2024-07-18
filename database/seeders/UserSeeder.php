<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// 追加分
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // データ挿入
        // Roleの数値の少ない方が権限強
        DB::table('users')->insert([
            [
                // 管理責任者
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password123'),
                'role' => 1, //Roleの設定
            ],
            [
                // 管理者
                'name' => 'manager',
                'email' => 'manager@manager.com',
                'password' => Hash::make('password123'),
                'role' => 5, //Roleの設定
            ],
            [
                // ユーザー会員
                'name' => 'test',
                'email' => 'test@test.com',
                'password' => Hash::make('password123'),
                'role' => 9, //Roleの設定
            ]
        ]);
    }
}
