<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blood_type_id')->constrained()->cascadeOnDelete();
            $table->integer('units_available')->default(0);
            $table->integer('min_threshold')->default(5);
            $table->timestamps(); // يقوم تلقائياً بإنشاء وتحديث updated_at عند أي تعديل للمخزون
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_inventories');
    }
};
