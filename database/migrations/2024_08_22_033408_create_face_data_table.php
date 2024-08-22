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
        Schema::create('face_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->longText('img');
            $table->timestamps();
             // Define the foreign key constraint
             $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Optional: define action on delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('face_data');
    }
};
