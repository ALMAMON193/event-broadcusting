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
            $table->unsignedBigInteger('sender_id');        // from doctor or patient or patient member table
            $table->string('sender_type');                  // doctor or patient or patient member table
            $table->unsignedBigInteger('receiver_id');      // to  doctor or patient or patient member table
            $table->string('receiver_type');                // doctor or patient or patient member table
            $table->text('message');
            $table->timestamps();
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
