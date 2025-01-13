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
        Schema::create('research_final_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('research_id');
            $table->text('summary');
            $table->string('keyword');
            $table->string('substance')->nullable();
            $table->string('partner_contribution')->nullable();
            $table->string('poster')->nullable();
            $table->string('video_profile')->nullable();
            $table->string('sptb')->nullable();
            $table->integer('no_sk');
            $table->integer('no_contract');
            $table->string('place_date');
            $table->integer('nip');
            $table->text('description_1');
            $table->integer('realization_1');
            $table->text('description_2');
            $table->integer('realization_2');
            $table->text('description_3');
            $table->integer('realization_3');
            $table->text('description_4');
            $table->integer('realization_4');
            $table->text('description_5');
            $table->integer('realization_5');
            $table->text('description_6');
            $table->integer('realization_6');
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamps();

            $table->foreign('research_id')->references('id')->on('research')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_final_reports');
    }
};
