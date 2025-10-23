<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientRecordsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('patient_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('date_processed');
            $table->string('case_type');
            $table->string('control_number');
            $table->string('claimant_name');
            $table->string('case_category');
            $table->string('patient_name');
            $table->longText('diagnosis')->nullable();
            $table->integer('age');
            $table->string('address');
            $table->string('contact_number');
            $table->string('case_worker');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('patient_records');
    }
}
