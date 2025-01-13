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
        Schema::create('budget_plan_services', function (Blueprint $table) {
            $table->id();
            $table->string('comunity_service_id');
            $table->foreign('comunity_service_id')->references('id')->on('comunity_services')->onDelete('cascade');
            $table->integer('year')->nullable();
            $table->unsignedBigInteger('id_group_budget')->nullable();
            $table->foreign('id_group_budget')->references('id')->on('budget_groups')->onDelete('set null');
            $table->unsignedBigInteger('id_component_budget')->nullable();
            $table->foreign('id_component_budget')->references('id')->on('budget_components')->onDelete('set null');
            $table->string('item')->nullable();
            $table->integer('unit')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('price_unit')->nullable();
            $table->integer('total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_plan_services');
    }
};
