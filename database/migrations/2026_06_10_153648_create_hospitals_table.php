<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('facility_name'); // اسم المنشأة
            $table->string('facility_type'); // نوع المنشأة (مستشفى، مركز طبي، إلخ)
            $table->string('manager_name')->nullable();  // اسم المدير المسؤول
            $table->string('license_file')->nullable(); // مسار ملف الترخيص (قابل ليكون خالياً)
            $table->string('license_number')->unique(); // رقم الترخيص الطبي
            $table->string('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->boolean('is_verified')->default(false); // التحقق من المستشفى من قبل الإدارة
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
