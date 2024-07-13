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
            $table->string('detail');
            $table->integer('max_people');
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->timestamps();
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
