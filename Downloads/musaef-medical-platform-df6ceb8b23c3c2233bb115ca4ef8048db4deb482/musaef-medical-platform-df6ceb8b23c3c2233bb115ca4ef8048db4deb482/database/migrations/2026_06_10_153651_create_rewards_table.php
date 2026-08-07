<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الشارة أو المكافأة
            $table->text('description')->nullable();
            $table->integer('points_required')->default(0); // النقاط المطلوبة للحصول عليها
            $table->string('icon_path')->nullable(); // مسار أيقونة الشارة
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};