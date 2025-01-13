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
        Schema::create('output_final_report1s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('final_report_id');
            $table->enum('status',['Tercapai','Tidak Tercapai']);
            $table->integer('recognized_sks');
            $table->string('recognized_courses');
            $table->string('proof_recognition')->nullable();
            $table->timestamps();

            $table->foreign('final_report_id')->references('id')->on('service_final_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('output_final_report1s');
    }
};
