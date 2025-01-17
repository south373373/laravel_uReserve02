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
        // table名は複数形になる。
        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            // 追記分
            $table->string('name');
            $table->text('information');
            $table->integer('max_people');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_visible');

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
        Schema::dropIfExists('conferences');
    }
};
