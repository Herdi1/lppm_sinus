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
        Schema::create('output_media_publications', function (Blueprint $table) {
            $table->id();
            $table->string('comunity_service_id');
            $table->foreign('comunity_service_id')->references('id')->on('comunity_services')->onDelete('cascade');
            $table->unsignedBigInteger('id_category_output')->nullable();
            $table->foreign('id_category_output')->references('id')->on('media_output_categories')->onDelete('set null');
            $table->unsignedBigInteger('id_type_output')->nullable();
            $table->foreign('id_type_output')->references('id')->on('media_output_types')->onDelete('set null');
            $table->boolean('status');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('output_media_publications');
    }
};
