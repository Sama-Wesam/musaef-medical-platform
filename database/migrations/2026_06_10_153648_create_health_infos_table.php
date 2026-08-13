<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('donors')->cascadeOnDelete();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->date('last_donation_date')->nullable();
            $table->boolean('has_chronic_diseases')->default(false);
            $table->text('diseases_description')->nullable();
            $table->json('questionnaire_answers')->nullable(); // تمت إضافته ليتطابق مع نموذج HealthInfo وتخزين إجابات الاستبيان
            $table->boolean('is_eligible')->default(true); // مؤهل طبياً للتبرع
            $table->text('rejection_reason')->nullable(); // سبب الرفض إن وجد
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_infos');
    }
};
