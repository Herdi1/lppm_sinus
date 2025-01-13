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
        Schema::create('research_status_changes', function (Blueprint $table) {
            $table->id();
            $table->string('research_id');
            $table->unsignedBigInteger('prev_status_id')->nullable();
            $table->unsignedBigInteger('new_status_id');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->foreign('research_id')->references('id')->on('research')->onDelete('cascade');
            $table->foreign('prev_status_id')->references('id')->on('statuses')->onDelete('set null');
            $table->foreign('new_status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_status_changes', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['prev_status_id']);
            $table->dropForeign(['new_status_id']);

            // Optionally, if reverting completely:
            $table->integer('prev_status')->nullable()->change(); // Revert to integer status
            $table->integer('new_status')->change();  // Revert to integer status
        });
    }
};
