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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('comunity_service_id');
            $table->foreign('comunity_service_id')->references('id')->on('comunity_services')->onDelete('cascade');
            $table->string('name');
            $table->string('province');
            $table->string('leader_name');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('partner_groups');
            $table->unsignedBigInteger('partner_type_id')->nullable();
            $table->foreign('partner_type_id')->references('id')->on('partner_types');
            $table->string('email');
            $table->integer('funding_contribution');
            $table->string('document');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
