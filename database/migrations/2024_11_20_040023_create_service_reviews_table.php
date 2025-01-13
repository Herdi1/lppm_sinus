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
        Schema::create('service_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('comunity_service_id');
            $table->unsignedBigInteger('reviewer_id');
            $table->boolean('indicator_1')->default(false);
            $table->boolean('indicator_2')->default(false);
            $table->boolean('indicator_3')->default(false);
            $table->boolean('indicator_4')->default(false);
            $table->boolean('indicator_5')->default(false);
            $table->boolean('indicator_6')->default(false);
            $table->boolean('indicator_7')->default(false);
            $table->boolean('indicator_8')->default(false);
            $table->boolean('indicator_9')->default(false);
            $table->boolean('indicator_10')->default(false);
            $table->boolean('indicator_11')->default(false);
            $table->boolean('indicator_12')->default(false);
            $table->integer('substance_score_1')->default('0');
            $table->integer('substance_score_2')->default('0');
            $table->integer('substance_score_3')->default('0');
            $table->integer('substance_score_4')->default('0');
            $table->integer('substance_score_5')->default('0');
            $table->integer('substance_score_6')->default('0');
            $table->integer('substance_score_7')->default('0');
            $table->integer('substance_score_8')->default('0');
            $table->integer('substance_score_9')->default('0');
            $table->integer('substance_score_10')->default('0');
            $table->integer('substance_score_11')->default('0');
            $table->integer('substance_score_12')->default('0');
            $table->integer('substance_score_13')->default('0');
            $table->integer('substance_score_14')->default('0');
            $table->integer('substance_score_15')->default('0');
            $table->integer('substance_score_16')->default('0');
            $table->integer('substance_score_17')->default('0');
            $table->integer('substance_score_18')->default('0');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('comunity_service_id')->references('id')->on('comunity_services')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reviews');
    }
};
