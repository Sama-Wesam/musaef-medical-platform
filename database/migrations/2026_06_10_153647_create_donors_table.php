<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blood_type_id')->nullable()->constrained()->nullOnDelete();
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female']);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_available')->default(true);
            $table->enum('eligibility_status', ['eligible', 'deferred', 'ineligible'])->default('eligible');
            $table->date('deferral_date')->nullable();
            $table->date('last_donation_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
