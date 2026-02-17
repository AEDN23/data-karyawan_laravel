@extends('layouts.app')

@section('actions')
    <a href="{{ route('karyawan.index') }}" class="btn btn-outline-info btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header-custom">
            <i class="fas fa-edit"></i> {{ isset($karyawan) ? 'Ubah' : 'Data Pribadi' }} Calon Karyawan
        </div>
        <div class="card-body p-4">
            <form action="{{ isset($karyawan) ? route('karyawan.update', $karyawan->id) : route('karyawan.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($karyawan)) @method('PUT') @endif

                <div class="row">
                    <!-- Data Inti -->
                    <div class="col-md-9">
                        <div class="section-title">
                            <i class="fas fa-user"></i> Data Pribadi
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIK</label>
                                <input type="text" name="nik" class="form-control"
                                    value="{{ old('nik', $karyawan->nik ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama <span class="text-required">*</span></label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ old('nama', $karyawan->nama ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tempat Lahir <span class="text-required">*</span></label>
                                <input type="text" name="tempat_lahir" class="form-control"
                                    value="{{ old('tempat_lahir', $karyawan->detail->tempat_lahir ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Lahir <span class="text-required">*</span></label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                    value="{{ old('tanggal_lahir', $karyawan->detail->tanggal_lahir ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP <span class="text-required">*</span></label>
                                <input type="text" name="no_hp" class="form-control"
                                    value="{{ old('no_hp', $karyawan->no_hp ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-required">*</span></label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $karyawan->email ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-required">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="Bekerja" {{ old('status', $karyawan->status ?? '') == 'Bekerja' ? 'selected' : '' }}>Bekerja</option>
                                    <option value="Tidak Bekerja" {{ old('status', $karyawan->status ?? '') == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-required">*</span></label>
                                <div class="mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki"
                                            {{ (old('jenis_kelamin', $karyawan->detail->jenis_kelamin ?? '') == 'Laki-laki') ? 'checked' : '' }} required>
                                        <label class="form-check-label">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan"
                                            {{ (old('jenis_kelamin', $karyawan->detail->jenis_kelamin ?? '') == 'Perempuan') ? 'checked' : '' }} required>
                                        <label class="form-check-label">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agama <span class="text-required">*</span></label>
                                <select name="agama" class="form-select" required>
                                    <option value="">-- Pilih Agama --</option>
                                    @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'] as $agm)
                                        <option value="{{ $agm }}" {{ (old('agama', $karyawan->detail->agama ?? '') == $agm) ? 'selected' : '' }}>{{ $agm }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Alamat <span class="text-required">*</span></label>
                                <textarea name="alamat" class="form-control" rows="2"
                                    required>{{ old('alamat', $karyawan->detail->alamat ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- upload foto -->
                    <div class="col-md-3">
                        <div class="text-center">
                            <label class="form-label d-block text-start">Foto Pegawai</label>
                            <div class="mb-3">
                                <div id="imagePreviewContainer">
                                    @if(isset($karyawan) && $karyawan->detail->foto)
                                        <img src="{{ asset('storage/' . $karyawan->detail->foto) }}" id="previewFoto"
                                            class="img-thumbnail mb-2" style="height: 200px; width: 100%; object-fit: cover;">
                                    @else
                                        <div id="placeholderFoto"
                                            class="bg-light border rounded d-flex align-items-center justify-content-center mb-2"
                                            style="height: 200px">
                                            <i class="fas fa-user fa-5x text-muted"></i>
                                        </div>
                                        <img src="" id="previewFoto" class="img-thumbnail mb-2 d-none"
                                            style="height: 200px; width: 100%; object-fit: cover;">
                                    @endif
                                </div>
                                <input type="file" name="foto" id="inputFoto" class="form-control form-control-sm"
                                    accept="image/*">
                            </div>
                        </div>
                    </div>

                    <script>
                        document.getElementById('inputFoto').addEventListener('change', function (event) {
                            const file = event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    const preview = document.getElementById('previewFoto');
                                    const placeholder = document.getElementById('placeholderFoto');

                                    preview.src = e.target.result;
                                    preview.classList.remove('d-none');
                                    if (placeholder) placeholder.classList.add('d-none');
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    </script>
                </div>

                <div class="row">
                    <!-- Pendidikan -->
                    <div class="col-md-6">
                        <div class="section-title">
                            <i class="fas fa-graduation-cap"></i> Pendidikan
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label class="form-label">Pendidikan Terakhir <span class="text-required">*</span></label>
                                <select name="pendidikan_terakhir" class="form-select" required>
                                    <option value="">-- Pilih Pendidikan --</option>
                                    @foreach(['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2'] as $p)
                                        <option value="{{ $p }}" {{ (old('pendidikan_terakhir', $karyawan->detail->pendidikan_terakhir ?? '') == $p) ? 'selected' : '' }}>{{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jurusan <span class="text-required">*</span></label>
                                <input type="text" name="jurusan" class="form-control"
                                    value="{{ old('jurusan', $karyawan->detail->jurusan ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Institusi <span class="text-required">*</span></label>
                                <input type="text" name="nama_instansi_pendidikan" class="form-control"
                                    value="{{ old('nama_instansi_pendidikan', $karyawan->detail->nama_instansi_pendidikan ?? '') }}"
                                    required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Pendidikan Informal (Kursus/Pelatihan)</label>
                                <textarea name="pendidikan_informal" class="form-control"
                                    rows="2">{{ old('pendidikan_informal', $karyawan->detail->pendidikan_informal ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Status Keluarga -->
                    <div class="col-md-6">
                        <div class="section-title">
                            <i class="fas fa-users"></i> Data Keluarga & Orang Tua
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Nikah <span class="text-required">*</span></label>
                                <select name="status_nikah" class="form-select" required>
                                    <option value="">-- Pilih Status --</option>
                                    @foreach(['Lajang', 'Menikah', 'Cerai'] as $s)
                                        <option value="{{ $s }}" {{ (old('status_nikah', $karyawan->detail->status_nikah ?? '') == $s) ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Anak <span class="text-required">*</span></label>
                                <input type="number" name="jumlah_anak" class="form-control"
                                    value="{{ old('jumlah_anak', $karyawan->detail->jumlah_anak ?? 0) }}" required>
                            </div>
                            <!-- Ayah -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama Ayah</label>
                                <input type="text" name="nama_ayah" class="form-control"
                                    value="{{ old('nama_ayah', $karyawan->detail->nama_ayah ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Thn Lahir Ayah</label>
                                <input type="number" name="tahun_lahir_ayah" class="form-control"
                                    value="{{ old('tahun_lahir_ayah', $karyawan->detail->tahun_lahir_ayah ?? '') }}"
                                    placeholder="YYYY">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pekerjaan Ayah</label>
                                <input type="text" name="pekerjaan_ayah" class="form-control"
                                    value="{{ old('pekerjaan_ayah', $karyawan->detail->pekerjaan_ayah ?? '') }}">
                            </div>
                            <!-- Ibu -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama Ibu</label>
                                <input type="text" name="nama_ibu" class="form-control"
                                    value="{{ old('nama_ibu', $karyawan->detail->nama_ibu ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Thn Lahir Ibu</label>
                                <input type="number" name="tahun_lahir_ibu" class="form-control"
                                    value="{{ old('tahun_lahir_ibu', $karyawan->detail->tahun_lahir_ibu ?? '') }}"
                                    placeholder="YYYY">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pekerjaan Ibu</label>
                                <input type="text" name="pekerjaan_ibu" class="form-control"
                                    value="{{ old('pekerjaan_ibu', $karyawan->detail->pekerjaan_ibu ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Dokumen -->
                <div class="section-title">
                    <i class="fas fa-file-pdf"></i> Upload Dokumen & Catatan
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Upload CV (PDF)</label>
                        <input type="file" name="cv" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Upload Sertifikat</label>
                        <input type="file" name="sertifikat" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Dokumen Lainnya</label>
                        <input type="file" name="dokumen_lain" class="form-control">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Catatan Tambahan / Keterangan</label>
                        <textarea name="catatan" class="form-control"
                            rows="3">{{ old('catatan', $karyawan->detail->catatan ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Pengalaman Kerja -->
                <div class="section-title">
                    <i class="fas fa-briefcase"></i> Pengalaman Kerja & Referensi
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light h-100">
                            <h6 class="fw-bold mb-3">Pengalaman 1</h6>
                            <input type="text" name="nama_perusahaan1" class="form-control mb-2"
                                placeholder="Nama Perusahaan"
                                value="{{ old('nama_perusahaan1', $karyawan->pengalaman->nama_perusahaan1 ?? '') }}">
                            <input type="text" name="jabatan1" class="form-control mb-2" placeholder="Jabatan"
                                value="{{ old('jabatan1', $karyawan->pengalaman->jabatan1 ?? '') }}">
                            <input type="text" name="masa_kerja1" class="form-control mb-2" placeholder="Masa Kerja (Thn)"
                                value="{{ old('masa_kerja1', $karyawan->pengalaman->masa_kerja1 ?? '') }}">
                            <input type="number" name="gaji_terakhir1" class="form-control mb-2" placeholder="Gaji Terakhir"
                                value="{{ old('gaji_terakhir1', $karyawan->pengalaman->gaji_terakhir1 ?? '') }}">
                            <textarea name="alasan_keluar1" class="form-control" rows="2"
                                placeholder="Alasan Keluar">{{ old('alasan_keluar1', $karyawan->pengalaman->alasan_keluar1 ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light h-100">
                            <h6 class="fw-bold mb-3">Pengalaman 2</h6>
                            <input type="text" name="nama_perusahaan2" class="form-control mb-2"
                                placeholder="Nama Perusahaan"
                                value="{{ old('nama_perusahaan2', $karyawan->pengalaman->nama_perusahaan2 ?? '') }}">
                            <input type="text" name="jabatan2" class="form-control mb-2" placeholder="Jabatan"
                                value="{{ old('jabatan2', $karyawan->pengalaman->jabatan2 ?? '') }}">
                            <input type="text" name="masa_kerja2" class="form-control mb-2" placeholder="Masa Kerja (Thn)"
                                value="{{ old('masa_kerja2', $karyawan->pengalaman->masa_kerja2 ?? '') }}">
                            <input type="number" name="gaji_terakhir2" class="form-control mb-2" placeholder="Gaji Terakhir"
                                value="{{ old('gaji_terakhir2', $karyawan->pengalaman->gaji_terakhir2 ?? '') }}">
                            <textarea name="alasan_keluar2" class="form-control" rows="2"
                                placeholder="Alasan Keluar">{{ old('alasan_keluar2', $karyawan->pengalaman->alasan_keluar2 ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border border-info rounded bg-info bg-opacity-10 h-100">
                            <h6 class="fw-bold mb-3 text-info">Referensi Internal Group</h6>
                            <input type="text" name="nama_pt_group" class="form-control border-info mb-2"
                                placeholder="Nama PT Group"
                                value="{{ old('nama_pt_group', $karyawan->pengalaman->nama_pt_group ?? '') }}">
                            <input type="text" name="departemen_group" class="form-control border-info mb-2"
                                placeholder="Departemen"
                                value="{{ old('departemen_group', $karyawan->pengalaman->departemen_group ?? '') }}">
                            <input type="text" name="jabatan_group" class="form-control border-info mb-2"
                                placeholder="Jabatan"
                                value="{{ old('jabatan_group', $karyawan->pengalaman->jabatan_group ?? '') }}">
                            <textarea name="alasan_keluar_group" class="form-control border-info" rows="2"
                                placeholder="Alasan Resign">{{ old('alasan_keluar_group', $karyawan->pengalaman->alasan_keluar_group ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-end border-top pt-3">
                    <button type="submit" class="btn btn-info text-white px-5 shadow-sm">
                        <i class="fas fa-save me-2"></i> {{ isset($karyawan) ? 'Update Data' : 'Simpan Data' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection