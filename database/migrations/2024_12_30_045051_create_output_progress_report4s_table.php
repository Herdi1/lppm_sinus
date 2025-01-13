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
        Schema::create('output_progress_report4s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->enum('status_article',['Submited','Accepted','Published','Draft']);
            $table->enum('status_writer',['First Author','Co-Author','Author']);
            $table->string('journal_name');
            $table->string('issn_eissn');
            $table->string('indexing_agency');
            $table->string('journal_url');
            $table->string('title_article');
            $table->string('manuscript_article')->nullable();
            $table->string('proof_submit')->nullable();
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('service_progress_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('output_progress_report4s');
    }
};
