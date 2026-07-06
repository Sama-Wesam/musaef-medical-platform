<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان المقال
            $table->longText('content'); // محتوى المقال التفصيلي
            $table->string('image_path')->nullable(); // مسار الصورة التوضيحية (اختياري)
            $table->boolean('is_published')->default(true); // حالة النشر (ظاهر للزوار أم مخفي)
            $table->timestamps(); // يسجل تاريخ كتابة المقال وتاريخ آخر تعديل تلقائياً
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
