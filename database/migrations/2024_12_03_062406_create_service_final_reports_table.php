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
        Schema::create('service_final_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('comunity_service_id');
            $table->text('summary');
            $table->string('keyword');
            $table->string('substance');
            $table->string('partner_contribution');
            $table->enum('target_partners', ['Masyarakat Ekonnomi Produktif', 'Masyarakat Ekonnomi non Produktif']);
            $table->enum('productive_economic_society', ['Pengusaha Mitra/UMKM','Anggota Koperasi','Kelompok Petani','Kelompok Industri Rumah Tangga','Tidak Ada']);
            $table->enum('nonproductive_economic_society', ['Kelompok Pendidikan (PAUD, SD, SMP, SMA/SMK/Pesantren)','Kelompok PKK/Karang Taruna','Puskesmas/Posyandu','Tidak Ada']);
            $table->integer('number_of_partners');
            $table->enum('partner_education',['S3','S2','S1','Diploma','SMA','SMP','SD','Tidak Berpendidikan']);
            $table->enum('problem_areas', ['Teknologi','Manajemen','Sosial Ekonomi','Hukum','Keamanan']);
            $table->enum('distance_partners', ['<50 KM','50-100 KM','101-200 KM','>200 KM (Beda Provinsi)']);
            $table->integer('male_proposing_team');
            $table->integer('female_proposing_team');
            $table->integer('male_partners_team');
            $table->integer('female_partners_team');
            $table->integer('total_students');
            $table->string('male_student');
            $table->string('female_student');
            $table->enum('implementation_activities',['Penyuluhan','Pendampingan','Pendidikan','Demplot/Percontohan','Rancang Bangun','Pelatihan']);
            $table->integer('implementation_time');
            $table->enum('program_sustainability',['Berlanjut','Berhenti']);
            $table->integer('production_capacity_before_program');
            $table->integer('production_capacity_after_program');
            $table->integer('turnover_before_program');
            $table->integer('turnover_after_program');
            $table->string('funding_sources');
            $table->integer('funding_amount');
            $table->enum('partner_role',['Objek Kegiatan','Subjek Kegiatan']);
            $table->text('partner_role_active');
            $table->text('partner_role_passive');
            $table->enum('government_local_role',['Dukungan Dana','Dukungan Kebijakan','Dukungan Pelaksanaan Kegiatan']);
            $table->integer('funding_contribution');
            $table->string('budget_use');
            $table->timestamps();

            $table->foreign('comunity_service_id')->references('id')->on('comunity_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_final_reports');
    }
};
