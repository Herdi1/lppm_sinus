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
        Schema::create('output_final_result_research', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->enum('status_article', ['submitted', 'accepted', 'draft', 'published'])->default('draft');
            $table->enum('status_writer', ['first-author', 'co-author', 'author']);
            $table->string('journal_name');
            $table->string('issn');
            $table->string('indexing_agency');
            $table->string('journal_url');
            $table->string('title_article');
            $table->string('manuscript_article');
            $table->string('proof_submit');
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('research_final_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('output_final_result_research');
    }
};
