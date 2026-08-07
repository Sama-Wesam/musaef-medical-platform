<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blood_request_id')->nullable()->constrained()->nullOnDelete(); // في حال كان التبرع بناءً على طلب طوارئ
            $table->integer('units_donated')->default(1);
            $table->date('donation_date');
            $table->integer('points_earned')->default(0); // لنظام المكافآت (Gamification)
            $table->enum('status', ['successful', 'failed'])->default('successful');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};