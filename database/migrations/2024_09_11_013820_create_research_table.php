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
        Schema::create('research', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('tkt_current');
            $table->string('tkt_final');
            $table->unsignedBigInteger('scheme_id')->nullable();
            $table->unsignedBigInteger('scope_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('focus_id')->nullable();
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->unsignedBigInteger('cluster_lv1')->nullable();
            $table->unsignedBigInteger('cluster_lv2')->nullable();
            $table->unsignedBigInteger('cluster_lv3')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->integer('year');
            $table->integer('duration');
            $table->string('leader_name')->nullable();
            $table->string('leader_task')->nullable();
            $table->unsignedBigInteger('substance_id')->nullable();
            $table->string('substance')->nullable();
            $table->integer('approved_funds')->nullable();
            $table->string('letter_of_intent')->nullable();
            $table->unsignedBigInteger('status')->nullable();
            $table->unsignedBigInteger('period_id')->nullable();
            $table->timestamps();

            $table->foreign('substance_id')->references('id')->on('substances')->onDelete('set null');
            $table->foreign('scheme_id')->references('id')->on('schemes')->onDelete('set null');
            $table->foreign('scope_id')->references('id')->on('scopes')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('focus_id')->references('id')->on('research_focus')->onDelete('set null');
            $table->foreign('theme_id')->references('id')->on('research_themes')->onDelete('set null');
            $table->foreign('topic_id')->references('id')->on('research_topics')->onDelete('set null');
            $table->foreign('cluster_lv1')->references('id')->on('science_cluster1s')->onDelete('set null');
            $table->foreign('cluster_lv2')->references('id')->on('science_cluster2s')->onDelete('set null');
            $table->foreign('cluster_lv3')->references('id')->on('science_cluster3s')->onDelete('set null');
            $table->foreign('priority_id')->references('id')->on('research_priorities')->onDelete('set null');
            $table->foreign('status')->references('id')->on('statuses')->onDelete('set null');
            $table->foreign('period_id')->references('id')->on('activity_periods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research');
    }
};
