<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matching_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_request_id')->constrained()->cascadeOnDelete()->index(); // فهرس للبحث السريع عن طلبات الدم المرتبطة بالمطابقة
            $table->foreignId('donor_id')->constrained()->cascadeOnDelete();
            $table->decimal('match_score', 5, 2);
            $table->integer('eta_minutes')->nullable();
            $table->boolean('is_notified')->default(false)->index(); // فهرس أساسي لعمل الـ Polling لمعرفة المتبرعين الجدد الذين لم يتم إشعارهم بعد
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matching_results');
    }
};
