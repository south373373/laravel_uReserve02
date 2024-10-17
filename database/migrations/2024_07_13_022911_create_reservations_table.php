<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            // 追記分
            // 外部キー設定・同時更新設定
            // 追加要素として「予約(reservations)」のデータ(user_id・conference_id)が紐づけられていた場合でも削除可能に編集
            // $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            // $table->foreignId('conference_id')->constrained()->onUpdate('cascade');
            $table->foreignId('conference_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('number_of_people');
            $table->datetime('canceled_date')->nullable();

            $table->timestamps();
            // 削除用columnの追記
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
