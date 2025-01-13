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
        Schema::create('research_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('research_id');
            $table->unsignedBigInteger('reviewer_id');
            $table->boolean('indicator_1')->default(false);
            $table->boolean('indicator_2')->default(false);
            $table->boolean('indicator_3')->default(false);
            $table->boolean('indicator_4')->default(false);
            $table->boolean('indicator_5')->default(false);
            $table->boolean('indicator_6')->default(false);
            $table->integer('substance_score_1_1')->default('0');
            $table->integer('substance_score_1_2')->default('0');
            $table->integer('substance_score_1_3')->default('0');
            $table->integer('substance_score_2_1')->default('0');
            $table->integer('substance_score_2_2')->default('0');
            $table->integer('substance_score_2_3')->default('0');
            $table->integer('substance_score_3_1')->default('0');
            $table->integer('substance_score_3_2')->default('0');
            $table->integer('substance_score_3_3')->default('0');
            $table->integer('substance_score_4_1')->default('0');
            $table->integer('substance_score_4_2')->default('0');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('research_id')->references('id')->on('research')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_reviews');
    }
};
