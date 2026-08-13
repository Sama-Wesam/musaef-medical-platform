<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blood_type_id')->constrained()->cascadeOnDelete();
            $table->integer('units_required');
            $table->enum('emergency_level', ['normal', 'high', 'critical'])->default('normal');
            $table->enum('status', ['pending', 'searching', 'accepted', 'completed', 'cancelled'])->default('pending')->index(); // فهرس لتسريع استعلامات الطلبات المفتوحة
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
