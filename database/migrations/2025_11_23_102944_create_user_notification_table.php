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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('status_log_id')
                ->constrained('patient_status_logs')
                ->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate notifications
            $table->unique(['user_id', 'status_log_id']);

            // Indexes for better performance
            $table->index(['user_id', 'is_read']);
            $table->index(['status_log_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_notifications');
    }
};
