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

            // الاسم والنوع والعنوان باللغتين العربي والإنجليزي
            $table->string('facility_name'); // اسم المنشأة بالعربي
            $table->string('facility_name_en')->nullable(); // اسم المنشأة بالإنجليزي

            $table->string('facility_type'); // نوع المنشأة بالعربي
            $table->string('facility_type_en')->nullable(); // نوع المنشأة بالإنجليزي

            $table->string('manager_name')->nullable();  // اسم المدير المسؤول
            $table->string('license_file')->nullable(); // مسار ملف الترخيص
            $table->string('license_number')->unique(); // رقم الترخيص الطبي

            $table->string('address'); // العنوان بالعربي
            $table->string('address_en')->nullable(); // العنوان بالإنجليزي

            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->boolean('is_verified')->default(false); // التحقق من المستشفى
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
