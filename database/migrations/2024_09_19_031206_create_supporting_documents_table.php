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
        Schema::create('supporting_documents', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('research_id')->constrained()->onDelete('cascade');
            $table->string('research_id');
            $table->string('partner_name');
            $table->string('email');
            $table->string('institution');
            $table->string('country_code');
            $table->string('institution_address');
            $table->integer('funding_contribution1');
            $table->integer('funding_contribution2');
            $table->string('document')->nullable();
            $table->timestamps();

            $table->foreign('research_id')->references('id')->on('research')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supporting_documents');
    }
};
