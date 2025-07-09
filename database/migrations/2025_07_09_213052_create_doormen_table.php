<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doormen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('badge_number')->unique();
            $table->string('phone')->nullable();
            $table->enum('shift', ['morning', 'afternoon', 'night'])->default('morning');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['shift', 'active']);
            $table->index('badge_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doormen');
    }
};
