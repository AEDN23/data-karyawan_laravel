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
        Schema::table('karyawan_details', function (Blueprint $table) {
            $table->string('status_nikah', 50)->nullable()->change();
            $table->integer('jumlah_anak')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan_details', function (Blueprint $table) {
            $table->enum('status_nikah', ['Lajang', 'Menikah', 'Cerai'])->nullable(false)->change();
            $table->integer('jumlah_anak')->nullable(false)->change();
        });
    }
};
