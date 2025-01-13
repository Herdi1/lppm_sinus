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
        Schema::create('research_monevs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('research_id');
            $table->text('comment1');
            $table->text('comment2');
            $table->text('comment3');
            $table->text('comment4');
            $table->text('comment5');
            $table->text('comment6');
            $table->integer('score1');
            $table->integer('score2');
            $table->integer('score3');
            $table->integer('score4');
            $table->integer('score5');
            $table->text('reviewer_note');
            $table->timestamps();

            $table->foreign('research_id')->references('id')->on('research')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_monevs');
    }
};
