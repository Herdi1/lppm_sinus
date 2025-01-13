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
        Schema::create('outputs', function (Blueprint $table) {
            $table->id();
            $table->string('research_id');
            $table->integer('year');
            $table->unsignedBigInteger('id_category_output')->nullable();
            $table->unsignedBigInteger('id_type_output')->nullable();
            $table->enum('status', ['submitted', 'draft'])->default('draft');
            $table->text('description');
            $table->timestamps();

            $table->foreign('research_id')->references('id')->on('research')->onDelete('cascade');
            $table->foreign('id_type_output')->references('id')->on('output_types')->onDelete('set null');
            $table->foreign('id_category_output')->references('id')->on('output_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outputs');
    }
};
