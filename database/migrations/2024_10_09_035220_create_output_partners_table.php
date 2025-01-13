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
        Schema::create('output_partners', function (Blueprint $table) {
            $table->id();
            $table->string('comunity_service_id');
            $table->integer('year');
            $table->unsignedBigInteger('id_category_output')->nullable();
            $table->unsignedBigInteger('id_type_output')->nullable();
            $table->boolean('status');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('comunity_service_id')->references('id')->on('comunity_services')->onDelete('cascade');
            $table->foreign('id_category_output')->references('id')->on('partner_output_categories')->onDelete('set null');
            $table->foreign('id_type_output')->references('id')->on('partner_output_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('output_partners');
    }
};
