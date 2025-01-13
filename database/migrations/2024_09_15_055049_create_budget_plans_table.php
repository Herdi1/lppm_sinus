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
        Schema::create('budget_plans', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('research_id')->constrained()->onDelete('cascade');
            $table->string('research_id');
            $table->integer('year')->nullable();
            $table->unsignedBigInteger('id_group_budget')->nullable();
            $table->unsignedBigInteger('id_component_budget')->nullable();
            $table->string('item')->nullable();
            $table->integer('unit')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('price_unit')->nullable();
            $table->integer('total')->nullable();
            $table->timestamps();
            
            $table->foreign('research_id')->references('id')->on('research')->onDelete('cascade');
            $table->foreign('id_group_budget')->references('id')->on('budget_groups')->onDelete('set null');
            $table->foreign('id_component_budget')->references('id')->on('budget_components')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_plans');
    }
};
