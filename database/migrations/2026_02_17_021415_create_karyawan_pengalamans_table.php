<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawan_pengalamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');

            // PENGALAMAN KERJA KE 1 (Luar Group)
            $table->string('nama_perusahaan1', 100)->nullable();
            $table->string('jabatan1', 100)->nullable();
            $table->string('masa_kerja1', 50)->nullable();
            $table->decimal('gaji_terakhir1', 15, 2)->nullable();
            $table->text('alasan_keluar1')->nullable();

            // PENGALAMAN KERJA KE 2 (Luar Group)
            $table->string('nama_perusahaan2', 100)->nullable();
            $table->string('jabatan2', 100)->nullable();
            $table->string('masa_kerja2', 50)->nullable();
            $table->decimal('gaji_terakhir2', 15, 2)->nullable();
            $table->text('alasan_keluar2')->nullable();

            // REFERENSI DARI PT 1 GROUP
            $table->string('nama_pt_group', 100)->nullable();
            $table->string('jabatan_group', 100)->nullable();
            $table->string('departemen_group', 100)->nullable();
            $table->text('alasan_keluar_group')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_pengalamans');
    }
};
