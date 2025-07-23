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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); // 'doctor', 'patient', 'patient_member'
            $table->unsignedBigInteger('receiver_id');
            $table->string('receiver_type'); // 'doctor', 'patient', 'patient_member'
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['sender_id', 'sender_type']);
            $table->index(['receiver_id', 'receiver_type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
