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
        Schema::create('karyawan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');

            // Data Pribadi
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('agama', 50);
            $table->text('alamat_ktp');
            $table->text('alamat_domisili')->nullable();
            $table->enum('status_nikah', ['Lajang', 'Menikah', 'Cerai']);
            $table->integer('jumlah_anak')->default(0);

            // Pendidikan
            $table->string('pendidikan_terakhir', 50);
            $table->string('jurusan', 100);
            $table->string('nama_instansi_pendidikan', 100);
            $table->text('pendidikan_informal')->nullable();

            // Data Orang Tua
            $table->string('nama_ayah', 100);
            $table->year('tahun_lahir_ayah')->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('nama_ibu', 100);
            $table->year('tahun_lahir_ibu')->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();

            // File & Dokumen
            $table->string('foto')->nullable();
            $table->string('cv')->nullable();
            $table->string('sertifikat')->nullable();
            $table->string('dokumen_lain')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_details');
    }
};
