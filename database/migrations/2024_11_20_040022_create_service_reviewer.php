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
        Schema::create('service_reviewer', function (Blueprint $table) {
            $table->id();
            $table->string('comunity_service_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('comunity_service_id')->references('id')->on('comunity_services')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['comunity_service_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reviewer');
    }
};
