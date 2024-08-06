<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conference>
 */
class ConferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 範囲は10時-20時
        $availableHour = $this->faker->numberBetween(10, 20);
        // 区切り-00分・30分
        $minutes = [0,30];
        // ランダムにkeyを取得
        $mKey = array_rand($minutes);
        // イベント時間 1時間-3時間
        $addHour = $this->faker->numberBetween(1,3);

        // faker チートシート参照
        
        // 今月分の日時指定用の変数
        $dummyDate = $this->faker->dateTimeThisMonth;
        $startDate = $dummyDate->setTime($availableHour, $minutes[$mKey]);
        $clone = clone $startDate;
        $endDate = $clone->modify('+'.$addHour.'hour');
        // 出力確認
        // dd($startDate, $endDate);

        return [
            //ダミーデータの作成
            'name' => $this->faker->name,
            'information' => $this->faker->realText,
            'max_people' => $this->faker->numberBetween(1,20),
            // 以下の2つのcolumnは作成した「$dummyDate」にて設定
            // end_dateはmodifyにて1時間追加の日時にて設定
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_visible' => $this->faker->boolean,
        ];
    }
}
