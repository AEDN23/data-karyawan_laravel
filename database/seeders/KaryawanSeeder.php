<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan;
use App\Models\KaryawanDetail;
use App\Models\KaryawanPengalaman;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 110; $i++) {
            DB::transaction(function () use ($faker) {
                // 1. Create Karyawan
                $karyawan = Karyawan::create([
                    'nik' => $faker->unique()->numerify('################'),
                    'nama' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'no_hp' => $faker->numerify('08##########'),
                    'status' => $faker->randomElement(['Bekerja', 'Tidak Bekerja']),
                ]);

                // 2. Create KaryawanDetail
                KaryawanDetail::create([
                    'karyawan_id' => $karyawan->id,
                    'tempat_lahir' => $faker->city,
                    'tanggal_lahir' => $faker->date('Y-m-d', '2005-01-01'),
                    'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                    'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
                    'alamat' => $faker->address,
                    'status_nikah' => $faker->randomElement(['Lajang', 'Menikah', 'Cerai']),
                    'jumlah_anak' => $faker->numberBetween(0, 5),
                    'pendidikan_terakhir' => $faker->randomElement(['SMA/SMK', 'D3', 'S1', 'S2']),
                    'jurusan' => $faker->jobTitle,
                    'nama_instansi_pendidikan' => $faker->company . ' University',
                    'pendidikan_informal' => $faker->sentence,
                    'nama_ayah' => $faker->name('male'),
                    'tahun_lahir_ayah' => $faker->year('1980'),
                    'pekerjaan_ayah' => $faker->jobTitle,
                    'nama_ibu' => $faker->name('female'),
                    'tahun_lahir_ibu' => $faker->year('1980'),
                    'pekerjaan_ibu' => $faker->jobTitle,
                    'catatan' => $faker->paragraph,
                ]);

                // 3. Create KaryawanPengalaman
                KaryawanPengalaman::create([
                    'karyawan_id' => $karyawan->id,
                    'nama_perusahaan1' => $faker->company,
                    'jabatan1' => $faker->jobTitle,
                    'masa_kerja1' => $faker->numberBetween(1, 10) . ' Tahun',
                    'gaji_terakhir1' => $faker->numberBetween(3000000, 15000000),
                    'alasan_keluar1' => $faker->sentence,
                    'nama_perusahaan2' => $faker->company,
                    'jabatan2' => $faker->jobTitle,
                    'masa_kerja2' => $faker->numberBetween(1, 10) . ' Tahun',
                    'gaji_terakhir2' => $faker->numberBetween(3000000, 15000000),
                    'alasan_keluar2' => $faker->sentence,
                    'nama_pt_group' => $faker->randomElement(['PT. Elastomix Indonesia', 'PT. Chemco Harapan Nusantara', null]),
                    'jabatan_group' => $faker->jobTitle,
                    'departemen_group' => $faker->word,
                    'alasan_keluar_group' => $faker->sentence,
                ]);
            });
        }
    }
}
