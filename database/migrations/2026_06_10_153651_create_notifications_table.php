<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // support UUID primary key used by Laravel Database Notifications
            $table->nullableMorphs('notifiable');
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('type')->default('info')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('related_type')->nullable();
            $table->text('data')->nullable(); // ⚡ العمود المطلوب لإشعارات Laravel Database Notifications
            $table->boolean('is_read')->default(false)->index(); // فهرس لتسريع استعلامات الإشعارات غير المقروءة عبر الـ Polling
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
