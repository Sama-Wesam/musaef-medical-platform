<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reward_id')->nullable()->constrained()->nullOnDelete(); // إذا كانت الحركة عبارة عن فتح شارة
            $table->integer('points')->default(0); // عدد النقاط المكتسبة أو المخصومة
            $table->enum('type', ['earned', 'redeemed']); // حالة الحركة (كسب أو استبدال)
            $table->string('description')->nullable(); // سبب كسب النقاط (مثال: تبرع طارئ ناجح)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_transactions');
    }
};