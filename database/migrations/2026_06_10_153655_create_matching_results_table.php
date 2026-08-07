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
            $table->foreignId('blood_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('donor_id')->constrained()->cascadeOnDelete();
            $table->decimal('match_score', 5, 2); // نسبة المطابقة (مثال: 98.50%)
            $table->integer('eta_minutes')->nullable(); // وقت الوصول المتوقع المحسوب عبر الذكاء الاصطناعي
            $table->boolean('is_notified')->default(false); // هل تم إرسال إشعار لهذا المتبرع بناءً على النتيجة؟
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matching_results');
    }
};