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
        Schema::create('supporting_files', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('comunity_service_id')->constrained()->onDelete('cascade');
            $table->string('comunity_service_id');
            $table->foreign('comunity_service_id')->references('id')->on('comunity_services')->onDelete('cascade');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('supporting_file_types');
            $table->string('document');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supporting_files');
    }
};
