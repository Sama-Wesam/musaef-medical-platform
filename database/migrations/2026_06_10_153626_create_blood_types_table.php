<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // A+, O-, AB+, etc.
            $table->boolean('is_rare')->default(false); // لتمييز الفصائل النادرة
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_types');
    }
};