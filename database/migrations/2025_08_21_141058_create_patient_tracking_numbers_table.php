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
        Schema::create('patient_tracking_numbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('patient_id')
                ->constrained('patient_records')
                ->onDelete('cascade');
            $table->string('tracking_number')->unique();
            $table->timestamp('tracking_created_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_tracking_numbers');
    }
};
