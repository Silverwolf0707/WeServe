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
        Schema::create('online_patient_application', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('case_type');
            $table->string('claimant_name');
            $table->string('case_category');
            $table->string('applicant_name');
            $table->longText('diagnosis');
            $table->integer('age');
            $table->string('address');
            $table->string('contact_number');
            $table->string('tracking_number');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_patient_application');
    }
};
