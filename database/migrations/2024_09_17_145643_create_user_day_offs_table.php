<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_day_offs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('day_off_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_day_offs');
    }
};
