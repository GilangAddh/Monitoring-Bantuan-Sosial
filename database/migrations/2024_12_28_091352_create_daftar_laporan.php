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
        Schema::create('daftar_laporan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program');
            $table->integer('jumlah_penerima');
            $table->string('provinsi');
            $table->string('kabupaten');
            $table->string('kecamatan');
            $table->string('kode_provinsi');
            $table->string('kode_kabupaten');
            $table->string('kode_kecamatan');
            $table->string('bukti_penyaluran');
            $table->text('catatan')->nullable();
            $table->string('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_laporan');
    }
};
