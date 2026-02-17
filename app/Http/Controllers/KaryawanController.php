<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Karyawan;
use App\Models\KaryawanDetail;
use App\Models\KaryawanPengalaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KaryawanTemplateExport;
use App\Imports\KaryawanImport;

class KaryawanController extends Controller
{
    /**
     * Tampilkan semua karyawan dengan detailnya
     */
    public function index()
    {
        $karyawans = Karyawan::with(['detail', 'pengalaman'])->latest()->get();
        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * Form tambah karyawan
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Simpan data karyawan ke 3 tabel sekaligus
     */
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'nik' => 'nullable|unique:karyawans,nik',
            'nama' => 'required',
            'email' => 'required|email|unique:karyawans,email',
            'no_hp' => 'required',
            'status' => 'required|in:Bekerja,Tidak Bekerja',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'agama_lainnya' => 'required_if:agama,Lainnya',
            'alamat' => 'required',
            'status_nikah' => 'required',
            'jumlah_anak' => 'required|integer|min:0',
            'pendidikan_terakhir' => 'required',
            'jurusan' => 'required',
            'nama_instansi_pendidikan' => 'required',
            'tahun_lahir_ayah' => 'nullable|integer|min:1|max:9999',
            'tahun_lahir_ibu' => 'nullable|integer|min:1|max:9999',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan ke tabel karyawans (Data Inti)
            $karyawan = Karyawan::create($request->only(['nik', 'nama', 'email', 'no_hp', 'status']));

            // 2. Pisahkan Data untuk Tabel Details
            $detailFields = [
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'agama',
                'alamat',
                'status_nikah',
                'jumlah_anak',
                'pendidikan_terakhir',
                'jurusan',
                'nama_instansi_pendidikan',
                'pendidikan_informal',
                'nama_ayah',
                'tahun_lahir_ayah',
                'pekerjaan_ayah',
                'nama_ibu',
                'tahun_lahir_ibu',
                'pekerjaan_ibu',
                'catatan'
            ];
            $detailData = $request->only($detailFields);

            // Jika agama pilih lainnya, ambil nilainya dari input agama_lainnya
            if ($request->agama === 'Lainnya') {
                $detailData['agama'] = $request->agama_lainnya;
            }

            // Handle upload file
            $namaSlug = Str::slug($request->nama);
            $tgl = date('dmY-His');

            if ($request->hasFile('foto')) {
                $ext = $request->file('foto')->getClientOriginalExtension();
                $detailData['foto'] = $request->file('foto')->storeAs('uploads/foto', "foto-{$namaSlug}-{$tgl}.{$ext}", 'public');
            }
            if ($request->hasFile('cv')) {
                $ext = $request->file('cv')->getClientOriginalExtension();
                $detailData['cv'] = $request->file('cv')->storeAs('uploads/cv', "cv-{$namaSlug}-{$tgl}.{$ext}", 'public');
            }
            if ($request->hasFile('sertifikat')) {
                $ext = $request->file('sertifikat')->getClientOriginalExtension();
                $detailData['sertifikat'] = $request->file('sertifikat')->storeAs('uploads/sertifikat', "sertifikat-{$namaSlug}-{$tgl}.{$ext}", 'public');
            }
            if ($request->hasFile('dokumen_lain')) {
                $ext = $request->file('dokumen_lain')->getClientOriginalExtension();
                $detailData['dokumen_lain'] = $request->file('dokumen_lain')->storeAs('uploads/dokumen', "dokumen-{$namaSlug}-{$tgl}.{$ext}", 'public');
            }

            $karyawan->detail()->create($detailData);

            // 3. Pisahkan Data untuk Tabel Pengalamans
            $pengalamanFields = [
                'nama_perusahaan1',
                'jabatan1',
                'masa_kerja1',
                'gaji_terakhir1',
                'alasan_keluar1',
                'nama_perusahaan2',
                'jabatan2',
                'masa_kerja2',
                'gaji_terakhir2',
                'alasan_keluar2',
                'nama_pt_group',
                'jabatan_group',
                'departemen_group',
                'alasan_keluar_group'
            ];
            $pengalamanData = $request->only($pengalamanFields);

            $karyawan->pengalaman()->create($pengalamanData);

            DB::commit();

            return redirect()->route('karyawan.index')->with('success', 'Data Karyawan berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail karyawan (Halaman Detail)
     */
    public function show($id)
    {
        $karyawan = Karyawan::with(['detail', 'pengalaman'])->findOrFail($id);
        return view('karyawan.show', compact('karyawan'));
    }

    /**
     * Form edit data karyawan
     */
    public function edit($id)
    {
        $karyawan = Karyawan::with(['detail', 'pengalaman'])->findOrFail($id);
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update data karyawan
     */
    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        // Validasi
        $request->validate([
            'nik' => 'nullable|unique:karyawans,nik,' . $id,
            'nama' => 'required',
            'email' => 'required|email|unique:karyawans,email,' . $id,
            'no_hp' => 'required',
            'status' => 'required|in:Bekerja,Tidak Bekerja',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'agama_lainnya' => 'required_if:agama,Lainnya',
            'alamat' => 'required',
            'status_nikah' => 'required',
            'jumlah_anak' => 'required|integer|min:0',
            'pendidikan_terakhir' => 'required',
            'jurusan' => 'required',
            'nama_instansi_pendidikan' => 'required',
            'tahun_lahir_ayah' => 'nullable|integer|min:1|max:9999',
            'tahun_lahir_ibu' => 'nullable|integer|min:1|max:9999',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update Tabel Utama
            $karyawan->update($request->only(['nik', 'nama', 'email', 'no_hp', 'status']));

            // 2. Update Detail
            $detailFields = [
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'agama',
                'alamat',
                'status_nikah',
                'jumlah_anak',
                'pendidikan_terakhir',
                'jurusan',
                'nama_instansi_pendidikan',
                'pendidikan_informal',
                'nama_ayah',
                'tahun_lahir_ayah',
                'pekerjaan_ayah',
                'nama_ibu',
                'tahun_lahir_ibu',
                'pekerjaan_ibu',
                'catatan'
            ];
            $detailData = $request->only($detailFields);

            // Jika agama pilih lainnya, ambil nilainya dari input agama_lainnya
            if ($request->agama === 'Lainnya') {
                $detailData['agama'] = $request->agama_lainnya;
            }

            $namaSlug = Str::slug($request->nama);
            $tgl = date('dmY-His');

            if ($request->hasFile('foto')) {
                if ($karyawan->detail->foto && Storage::disk('public')->exists($karyawan->detail->foto)) {
                    Storage::disk('public')->delete($karyawan->detail->foto);
                }
                $ext = $request->file('foto')->getClientOriginalExtension();
                $detailData['foto'] = $request->file('foto')->storeAs('uploads/foto', "foto-{$namaSlug}-{$tgl}.{$ext}", 'public');
            }
            if ($request->hasFile('cv')) {
                if ($karyawan->detail->cv && Storage::disk('public')->exists($karyawan->detail->cv)) {
                    Storage::disk('public')->delete($karyawan->detail->cv);
                }
                $ext = $request->file('cv')->getClientOriginalExtension();
                $detailData['cv'] = $request->file('cv')->storeAs('uploads/cv', "cv-{$namaSlug}-{$tgl}.{$ext}", 'public');
            }
            if ($request->hasFile('sertifikat')) {
                if ($karyawan->detail->sertifikat && Storage::disk('public')->exists($karyawan->detail->sertifikat)) {
                    Storage::disk('public')->delete($karyawan->detail->sertifikat);
                }
                $ext = $request->file('sertifikat')->getClientOriginalExtension();
                $detailData['sertifikat'] = $request->file('sertifikat')->storeAs('uploads/sertifikat', "sertifikat-{$namaSlug}-{$tgl}.{$ext}", 'public');
            }
            if ($request->hasFile('dokumen_lain')) {
                if ($karyawan->detail->dokumen_lain && Storage::disk('public')->exists($karyawan->detail->dokumen_lain)) {
                    Storage::disk('public')->delete($karyawan->detail->dokumen_lain);
                }
                $ext = $request->file('dokumen_lain')->getClientOriginalExtension();
                $detailData['dokumen_lain'] = $request->file('dokumen_lain')->storeAs('uploads/dokumen', "dokumen-{$namaSlug}-{$tgl}.{$ext}", 'public');
            }

            $karyawan->detail()->update($detailData);

            // 3. Update Pengalaman
            $pengalamanFields = [
                'nama_perusahaan1',
                'jabatan1',
                'masa_kerja1',
                'gaji_terakhir1',
                'alasan_keluar1',
                'nama_perusahaan2',
                'jabatan2',
                'masa_kerja2',
                'gaji_terakhir2',
                'alasan_keluar2',
                'nama_pt_group',
                'jabatan_group',
                'departemen_group',
                'alasan_keluar_group'
            ];
            $pengalamanData = $request->only($pengalamanFields);

            $karyawan->pengalaman()->update($pengalamanData);

            DB::commit();

            return redirect()->route('karyawan.index')->with('success', 'Data Karyawan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::with('detail')->findOrFail($id);

        // Hapus file fisik jika ada
        if ($karyawan->detail) {
            $fields = ['foto', 'cv', 'sertifikat', 'dokumen_lain'];
            foreach ($fields as $field) {
                if ($karyawan->detail->$field && Storage::disk('public')->exists($karyawan->detail->$field)) {
                    Storage::disk('public')->delete($karyawan->detail->$field);
                }
            }
        }

        $karyawan->delete(); // Cascade akan otomatis hapus detail & pengalaman
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    /**
     * Download Template Excel
     */
    public function exportTemplate()
    {
        return Excel::download(new KaryawanTemplateExport, 'template_import_karyawan.xlsx');
    }

    /**
     * Import Data Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $import = new KaryawanImport();
            Excel::import($import, $request->file('file'));

            return redirect()->back()->with([
                'import_success' => true,
                'success_count' => $import->successCount,
                'duplicate_count' => $import->duplicateCount
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
}
