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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');
            $table->string('visitor_name');
            $table->string('visitor_phone', 20)->nullable();
            $table->date('visit_date');
            
            $table->string('car_model')->nullable();
            $table->string('car_color')->nullable();
            $table->string('license_plate', 10)->nullable();
            
            $table->text('notes')->nullable();
            $table->enum('status', ['scheduled', 'confirmed', 'cancelled'])->default('scheduled');
            $table->timestamps();
            
            $table->index(['resident_id', 'visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
