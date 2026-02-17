<?php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KaryawanImport implements ToModel, WithStartRow
{
    public $successCount = 0;
    public $duplicateCount = 0;

    public function startRow(): int
    {
        return 2;
    }

    private function transformDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value);
            }

            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function transformYear($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Jika value adalah angka besar (> 2100), kemungkinan serial date Excel
            if (is_numeric($value) && $value > 2100) {
                return Date::excelToDateTimeObject($value)->format('Y');
            }
            return $value;
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function model(array $row)
    {
        // Skip jika Nama kosong (baris kosong)
        if (empty($row[1])) {
            return null;
        }

        // Cek NIK Unik
        if (!empty($row[0])) {
            $exists = Karyawan::where('nik', $row[0])->exists();
            if ($exists) {
                $this->duplicateCount++;
                return null;
            }
        }

        return DB::transaction(function () use ($row) {
            // 1. Simpan ke tabel karyawans
            $karyawan = Karyawan::create([
                'nik' => $row[0],
                'nama' => $row[1],
                'email' => $row[2],
                'no_hp' => $row[3],
            ]);

            // 2. Simpan ke tabel karyawan_details
            $karyawan->detail()->create([
                'tempat_lahir' => $row[4],
                'tanggal_lahir' => $this->transformDate($row[5]),
                'jenis_kelamin' => $row[6],
                'agama' => $row[7],
                'alamat' => $row[8],
                'status_nikah' => $row[9],
                'jumlah_anak' => $row[10] ?? 0,
                'pendidikan_terakhir' => $row[11],
                'jurusan' => $row[12],
                'nama_instansi_pendidikan' => $row[13],
                'pendidikan_informal' => $row[14],
                'nama_ayah' => $row[15],
                'tahun_lahir_ayah' => $this->transformYear($row[16]),
                'pekerjaan_ayah' => $row[17],
                'nama_ibu' => $row[18],
                'tahun_lahir_ibu' => $this->transformYear($row[19]),
                'pekerjaan_ibu' => $row[20],
                'catatan' => $row[21],
            ]);

            // 3. Simpan ke tabel karyawan_pengalamans
            $karyawan->pengalaman()->create([
                'nama_perusahaan1' => $row[22],
                'jabatan1' => $row[23],
                'masa_kerja1' => $row[24],
                'gaji_terakhir1' => $row[25],
                'alasan_keluar1' => $row[26],
                'nama_perusahaan2' => $row[27],
                'jabatan2' => $row[28],
                'masa_kerja2' => $row[29],
                'gaji_terakhir2' => $row[30],
                'alasan_keluar2' => $row[31],
                'nama_pt_group' => $row[32],
                'departemen_group' => $row[33],
                'jabatan_group' => $row[34],
                'alasan_keluar_group' => $row[35],
            ]);

            $this->successCount++;
            return $karyawan;
        });
    }
}

