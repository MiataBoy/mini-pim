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
        Schema::create('profile_rights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rights_id')->references('id')->on('rights');
            $table->foreignId('profile_id')->references('id')->on('profiles');
            $table->boolean('right_assigned')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_rights');
    }
};
