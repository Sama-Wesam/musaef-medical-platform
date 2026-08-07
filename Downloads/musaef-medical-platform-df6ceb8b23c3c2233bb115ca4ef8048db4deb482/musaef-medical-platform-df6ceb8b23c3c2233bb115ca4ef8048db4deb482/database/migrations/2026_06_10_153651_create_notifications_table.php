<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // المستخدم المستهدف
            $table->string('title');
            $table->text('body');
            $table->string('type')->default('info'); // emergency, info, reward, system
            $table->unsignedBigInteger('related_id')->nullable(); // لربط الإشعار بطلب دم أو مكافأة
            $table->string('related_type')->nullable(); // نوع العنصر المرتبط (Model)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};