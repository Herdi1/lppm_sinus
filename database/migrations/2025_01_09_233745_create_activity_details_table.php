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
        Schema::create('activity_details', function (Blueprint $table) {
            $table->id();
            $table->enum('activity', ['research', 'community_service']);
            $table->string('no_sk_lembaga');
            $table->string('nama_lembaga');
            $table->string('alamat_lembaga');
            $table->string('no_telp');
            $table->string('no_fax');
            $table->string('email');
            $table->string('website');
            $table->foreignId('id_kepala')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_details');
    }
};
