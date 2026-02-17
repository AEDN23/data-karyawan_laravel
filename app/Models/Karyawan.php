<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = ['nik', 'nama', 'email', 'no_hp'];

    /**
     * Relasi ke KaryawanDetail (1 to 1)
     */
    public function detail()
    {
        return $this->hasOne(KaryawanDetail::class, 'karyawan_id');
    }

    /**
     * Relasi ke KaryawanPengalaman (1 to 1 - karena kolom dijadikan satu row)
     */
    public function pengalaman()
    {
        return $this->hasOne(KaryawanPengalaman::class, 'karyawan_id');
    }
}
