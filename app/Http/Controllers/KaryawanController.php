<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\KaryawanDetail;
use App\Models\KaryawanPengalaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            $karyawan = Karyawan::create($request->only(['nik', 'nama', 'email', 'no_hp']));

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
            if ($request->hasFile('foto')) {
                $detailData['foto'] = $request->file('foto')->store('uploads/foto', 'public');
            }
            if ($request->hasFile('cv')) {
                $detailData['cv'] = $request->file('cv')->store('uploads/cv', 'public');
            }
            if ($request->hasFile('sertifikat')) {
                $detailData['sertifikat'] = $request->file('sertifikat')->store('uploads/sertifikat', 'public');
            }
            if ($request->hasFile('dokumen_lain')) {
                $detailData['dokumen_lain'] = $request->file('dokumen_lain')->store('uploads/dokumen', 'public');
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
            $karyawan->update($request->only(['nik', 'nama', 'email', 'no_hp']));

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

            if ($request->hasFile('foto')) {
                if ($karyawan->detail->foto && Storage::disk('public')->exists($karyawan->detail->foto)) {
                    Storage::disk('public')->delete($karyawan->detail->foto);
                }
                $detailData['foto'] = $request->file('foto')->store('uploads/foto', 'public');
            }
            if ($request->hasFile('cv')) {
                if ($karyawan->detail->cv && Storage::disk('public')->exists($karyawan->detail->cv)) {
                    Storage::disk('public')->delete($karyawan->detail->cv);
                }
                $detailData['cv'] = $request->file('cv')->store('uploads/cv', 'public');
            }
            if ($request->hasFile('sertifikat')) {
                if ($karyawan->detail->sertifikat && Storage::disk('public')->exists($karyawan->detail->sertifikat)) {
                    Storage::disk('public')->delete($karyawan->detail->sertifikat);
                }
                $detailData['sertifikat'] = $request->file('sertifikat')->store('uploads/sertifikat', 'public');
            }
            if ($request->hasFile('dokumen_lain')) {
                if ($karyawan->detail->dokumen_lain && Storage::disk('public')->exists($karyawan->detail->dokumen_lain)) {
                    Storage::disk('public')->delete($karyawan->detail->dokumen_lain);
                }
                $detailData['dokumen_lain'] = $request->file('dokumen_lain')->store('uploads/dokumen', 'public');
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
}
