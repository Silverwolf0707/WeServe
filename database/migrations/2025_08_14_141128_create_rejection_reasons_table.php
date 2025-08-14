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
        Schema::create('rejection_reasons', function (Blueprint $table) {
            $table->id();

            // Reference to patient_records
            $table->foreignId('patient_id')
                ->constrained('patient_records')
                ->onDelete('cascade');

            // Reference to patient_status_logs
            $table->foreignId('patient_status_log_id')
                ->constrained('patient_status_logs')
                ->onDelete('cascade');

            $table->string('reason');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rejection_reasons');
    }
};
