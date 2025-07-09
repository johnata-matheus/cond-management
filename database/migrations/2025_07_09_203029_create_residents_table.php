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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('apartment', 10);
            $table->string('block', 5)->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['apartment', 'block']);
            $table->index(['apartment', 'block']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
