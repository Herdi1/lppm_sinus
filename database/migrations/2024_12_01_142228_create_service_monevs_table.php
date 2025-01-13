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
        Schema::create('service_monevs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('comunity_service_id');
            $table->integer('presence_1')->default('0');
            $table->integer('presence_2')->default('0');
            $table->integer('presence_3')->default('0');
            $table->integer('presence_4')->default('0');
            $table->integer('presence_5')->default('0');
            $table->integer('article_publication')->default('0');
            $table->integer('publication_journal')->default('0');
            $table->integer('recognition_sks_1')->default('0');
            $table->integer('recognition_sks_2')->default('0');
            $table->integer('video_1')->default('0');
            $table->integer('video_2')->default('0');
            $table->integer('video_3')->default('0');
            $table->integer('video_4')->default('0');
            $table->integer('video_5')->default('0');
            $table->integer('video_6')->default('0');
            $table->integer('video_7')->default('0');
            $table->integer('video_8')->default('0');
            $table->integer('poster_1')->default('0');
            $table->integer('poster_2')->default('0');
            $table->integer('poster_3')->default('0');
            $table->integer('budget_usage_1')->default('0');
            $table->integer('budget_usage_2')->default('0');
            $table->integer('budget_usage_3')->default('0');
            $table->integer('empowerment_1')->default('0');
            $table->integer('empowerment_2')->default('0');
            $table->integer('empowerment_3')->default('0');
            $table->integer('empowerment_4')->default('0');
            $table->integer('empowerment_5')->default('0');
            $table->text('reviewer_note');
            $table->timestamps();

            $table->foreign('comunity_service_id')->references('id')->on('comunity_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_monevs');
    }
};
