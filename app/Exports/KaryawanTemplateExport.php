<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KaryawanTemplateExport implements WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Email',
            'No HP',
            'Status (Bekerja/Tidak Bekerja)',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Jenis Kelamin (Laki-laki/Perempuan)',
            'Agama',
            'Alamat',
            'Status Nikah (Lajang/Menikah/Cerai)',
            'Jumlah Anak',
            'Pendidikan Terakhir',
            'Jurusan',
            'Nama Instansi Pendidikan',
            'Pendidikan Informal',
            'Nama Ayah',
            'Tahun Lahir Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Tahun Lahir Ibu',
            'Pekerjaan Ibu',
            'Catatan',
            'Nama Perusahaan 1',
            'Jabatan 1',
            'Masa Kerja 1',
            'Gaji Terakhir 1',
            'Alasan Keluar 1',
            'Nama Perusahaan 2',
            'Jabatan 2',
            'Masa Kerja 2',
            'Gaji Terakhir 2',
            'Alasan Keluar 2',
            'Nama PT Group',
            'Departemen Group',
            'Jabatan Group',
            'Alasan Keluar Group'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
