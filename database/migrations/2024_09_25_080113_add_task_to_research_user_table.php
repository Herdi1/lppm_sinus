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
    {        Schema::table('research_user', function (Blueprint $table) {
            $table->string('task')->nullable();
            $table->string('research_roles')->nullable();
            $table->enum('status', ['pending', 'accepted', 'denied'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_user', function (Blueprint $table) {
            $table->dropColumn('task');
            $table->dropColumn('research_roles');
            $table->dropColumn('status');
        });
    }
};
