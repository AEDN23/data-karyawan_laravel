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
    public $failCount = 0;

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

        // Cek Nilai Wajib (Nama, Email, No HP)
        if (empty($row[2]) || empty($row[3])) {
            $this->failCount++;
            return null;
        }

        // Cek NIK & Email Unik
        $nik = !empty($row[0]) ? $row[0] : null;
        $email = !empty($row[2]) ? $row[2] : null;

        if ($nik || $email) {
            $exists = Karyawan::where(function ($query) use ($nik, $email) {
                if ($nik)
                    $query->where('nik', $nik);
                if ($email)
                    $query->orWhere('email', $email);
            })->exists();

            if ($exists) {
                $this->duplicateCount++;
                return null;
            }
        }

        try {
            return DB::transaction(function () use ($row) {
                // 1. Simpan ke tabel karyawans
                $karyawan = Karyawan::create([
                    'nik' => $row[0],
                    'nama' => $row[1],
                    'email' => $row[2],
                    'no_hp' => $row[3],
                    'status' => $row[4] ?? 'Bekerja',
                ]);

                // 2. Simpan ke tabel karyawan_details
                $karyawan->detail()->create([
                    'tempat_lahir' => $row[5],
                    'tanggal_lahir' => $this->transformDate($row[6]),
                    'jenis_kelamin' => $row[7],
                    'agama' => $row[8],
                    'alamat' => $row[9],
                    'status_nikah' => $row[10],
                    'jumlah_anak' => $row[11] ?? 0,
                    'pendidikan_terakhir' => $row[12],
                    'jurusan' => $row[13],
                    'nama_instansi_pendidikan' => $row[14],
                    'pendidikan_informal' => $row[15],
                    'nama_ayah' => $row[16],
                    'tahun_lahir_ayah' => $this->transformYear($row[17]),
                    'pekerjaan_ayah' => $row[18],
                    'nama_ibu' => $row[19],
                    'tahun_lahir_ibu' => $this->transformYear($row[20]),
                    'pekerjaan_ibu' => $row[21],
                    'catatan' => $row[22],
                ]);

                // 3. Simpan ke tabel karyawan_pengalamans
                $karyawan->pengalaman()->create([
                    'nama_perusahaan1' => $row[23],
                    'jabatan1' => $row[24],
                    'masa_kerja1' => $row[25],
                    'gaji_terakhir1' => $row[26],
                    'alasan_keluar1' => $row[27],
                    'nama_perusahaan2' => $row[28],
                    'jabatan2' => $row[29],
                    'masa_kerja2' => $row[30],
                    'gaji_terakhir2' => $row[31],
                    'alasan_keluar2' => $row[32],
                    'nama_pt_group' => $row[33],
                    'departemen_group' => $row[34],
                    'jabatan_group' => $row[35],
                    'alasan_keluar_group' => $row[36],
                ]);

                $this->successCount++;
                return $karyawan;
            });
        } catch (\Exception $e) {
            $this->failCount++;
            return null;
        }
    }
}

