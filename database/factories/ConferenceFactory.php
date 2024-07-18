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
        // faker チートシート参照
        
        // 今月分の日時指定用の変数
        $dummyDate = $this->faker->dateTimeThisMonth;

        return [
            //ダミーデータの作成
            'name' => $this->faker->name,
            'information' => $this->faker->realText,
            'max_people' => $this->faker->numberBetween(1,20),
            // 以下の2つのcolumnは作成した「$dummyDate」にて設定
            // end_dateはmodifyにて1時間追加の日時にて設定
            'start_date' => $dummyDate->format('Y-m-d H:i:s'),
            'end_date' => $dummyDate->modify('+1hour')->format('Y-m-d H:i:s'),
            'is_visible' => $this->faker->boolean,
        ];
    }
}
