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
        Schema::create('output_progress_report5s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->enum('status',['Terbit', 'Tidak Terbit']);
            $table->integer('type_media');
            $table->string('title');
            $table->string('name');
            $table->string('proof_support')->nullable();
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('service_progress_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('output_progress_report5s');
    }
};
