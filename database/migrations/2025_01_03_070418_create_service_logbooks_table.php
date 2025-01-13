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
        Schema::create('service_logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('comunity_service_id');
            $table->date('date_activity');
            $table->enum('group_budget',['Teknologi dan Inovasi','Biaya Pelatihan','Biaya Upah dan Jasa','Biaya Perjalanan','Biaya Lainnya']);
            $table->integer('nominal');
            $table->integer('file_number');
            $table->text('activity_description');
            $table->integer('percentage');
            $table->string('document')->nullable();
            $table->timestamps();

            $table->foreign('comunity_service_id')->references('id')->on('comunity_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_logbooks');
    }
};
