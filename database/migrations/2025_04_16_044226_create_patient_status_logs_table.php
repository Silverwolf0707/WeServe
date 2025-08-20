<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientStatusLogsTable extends Migration
{
    public function up()
    {
        Schema::create('patient_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                ->constrained('patient_records')
                ->onDelete('cascade');
            $table->string('status');
            $table->datetime('status_date');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_status_logs');
    }
}
