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
        Schema::create('comunity_services', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('service_categories')->onDelete('set null');
            $table->unsignedBigInteger('focus_thematic_id')->nullable();
            $table->foreign('focus_thematic_id')->references('id')->on('focus_thematics')->onDelete('set null');
            $table->unsignedBigInteger('focus_rirn_id')->nullable();
            $table->foreign('focus_rirn_id')->references('id')->on('focus_r_i_r_n_s')->onDelete('set null');
            $table->unsignedBigInteger('scheme_id')->nullable();
            $table->foreign('scheme_id')->references('id')->on('service_schemes')->onDelete('set null');
            $table->unsignedBigInteger('scope_id')->nullable();
            $table->foreign('scope_id')->references('id')->on('service_scopes')->onDelete('set null');
            $table->integer('year');
            $table->integer('duration');
            $table->unsignedBigInteger('cluster_lv1')->nullable();
            $table->foreign('cluster_lv1')->references('id')->on('science_cluster1s')->onDelete('set null');
            $table->unsignedBigInteger('cluster_lv2')->nullable();
            $table->foreign('cluster_lv2')->references('id')->on('science_cluster2s')->onDelete('set null');
            $table->unsignedBigInteger('cluster_lv3')->nullable();
            $table->foreign('cluster_lv3')->references('id')->on('science_cluster3s')->onDelete('set null');
            $table->string('leader_name');
            $table->string('leader_task');
            $table->string('substance_document');
            $table->integer('approved_funds')->nullable();
            $table->string('letter_of_intent')->nullable();
            $table->integer('status')->nullable();
            $table->unsignedBigInteger('period_id')->nullable();
            $table->foreign('period_id')->references('id')->on('activity_periods')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunity_services');
    }
};
