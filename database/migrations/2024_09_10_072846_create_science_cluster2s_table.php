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
        Schema::create('science_cluster2s', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('cluster1s_id')->nullable();
            $table->foreign('cluster1s_id')->references('id')->on('science_cluster1s')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('science_cluster2s');
    }
};
