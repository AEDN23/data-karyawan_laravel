@extends('layouts.app')

@section('actions')
    <a href="{{ route('karyawan.index') }}" class="btn btn-outline-info btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-cyan text-white py-3" style="background-color: #00bcd4;">
            <h5 class="mb-0"><i class="fas fa-id-card me-2"></i> Detail Data Karyawan</h5>
        </div>
        <div class="card-body p-0">
            <!-- header Profil -->
            <div class="d-flex align-items-center p-4 border-bottom bg-light">
                <div class="me-4 text-center">
                    @if($karyawan->detail && $karyawan->detail->foto)
                        <img src="{{ asset('public/storage/' . $karyawan->detail->foto) }}"
                            class="rounded-circle border border-4 border-white shadow" width="120" height="120"
                            style="object-fit: cover;">
                    @else
                        <div class="rounded-circle border border-4 border-white shadow d-flex align-items-center justify-content-center bg-secondary text-white"
                            width="120" height="120" style="height: 120px; width: 120px;">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="fw-bold mb-1">{{ $karyawan->nama }}</h3>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <p class="text-muted mb-0">NIK: {{ $karyawan->nik }}</p>
                        <span class="badge {{ $karyawan->status == 'Bekerja' ? 'bg-success' : 'bg-danger' }}">
                            {{ $karyawan->status }}
                        </span>
                    </div>
                    <div class="d-flex gap-4">
                        <span class="small"><i class="fas fa-phone text-info"></i> {{ $karyawan->no_hp }}</span>
                        <span class="small"><i class="fas fa-envelope text-info"></i> {{ $karyawan->email }}</span>
                        <span class="small"><i class="fas fa-map-marker-alt text-info"></i>
                            {{ $karyawan->detail->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="row p-4 g-4">
                <!-- Informasi Pribadi -->
                <div class="col-md-6">
                    <div class="section-title">
                        <i class="fas fa-user-circle"></i> Informasi Pribadi
                    </div>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="35%">Tempat, Tgl Lahir</td>
                            <td>: {{ $karyawan->detail->tempat_lahir }},
                                {{ \Carbon\Carbon::parse($karyawan->detail->tanggal_lahir)->format('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>: {{ $karyawan->detail->jenis_kelamin }}</td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td>: {{ $karyawan->detail->agama }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{ $karyawan->detail->alamat }}</td>
                        </tr>
                        <tr>
                            <td>Status Pernikahan</td>
                            <td>: {{ $karyawan->detail->status_nikah }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Anak</td>
                            <td>: {{ $karyawan->detail->jumlah_anak }} Anak</td>
                        </tr>
                    </table>
                </div>

                <!-- Data Keluarga -->
                <div class="col-md-6">
                    <div class="section-title">
                        <i class="fas fa-users"></i> Data Keluarga (Orang Tua)
                    </div>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="35%">Nama Ayah</td>
                            <td>: {{ $karyawan->detail->nama_ayah }}</td>
                        </tr>
                        <tr>
                            <td>Tahun Lahir Ayah</td>
                            <td>: {{ $karyawan->detail->tahun_lahir_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nama Ibu</td>
                            <td>: {{ $karyawan->detail->nama_ibu }}</td>
                        </tr>
                        <tr>
                            <td>Tahun Lahir Ibu</td>
                            <td>: {{ $karyawan->detail->tahun_lahir_ibu ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Riwayat Pendidikan -->
                <div class="col-12">
                    <div class="section-title">
                        <i class="fas fa-graduation-cap"></i> Riwayat Pendidikan
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1 fw-bold">Pendidikan Formal:</p>
                            <p class="mb-3">{{ $karyawan->detail->pendidikan_terakhir }} -
                                {{ $karyawan->detail->jurusan }}<br>
                                <small class="text-muted">{{ $karyawan->detail->nama_instansi_pendidikan }}</small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 fw-bold">Pendidikan Informal:</p>
                            <p class="mb-0">{{ $karyawan->detail->pendidikan_informal ?? 'Tidak ada' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pengalaman Kerja -->
                <div class="col-12">
                    <div class="section-title">
                        <i class="fas fa-briefcase"></i> Pengalaman Kerja
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded">
                                <h6 class="fw-bold">1. {{ $karyawan->pengalaman->nama_perusahaan1 ?? 'Tidak ada' }}</h6>
                                <small class="text-muted d-block">Jabatan:
                                    {{ $karyawan->pengalaman->jabatan1 ?? '-' }}</small>
                                <small class="text-muted">Masa Kerja:
                                    {{ $karyawan->pengalaman->masa_kerja1 ?? '-' }}</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded">
                                <h6 class="fw-bold">2. {{ $karyawan->pengalaman->nama_perusahaan2 ?? 'Tidak ada' }}</h6>
                                <small class="text-muted d-block">Jabatan:
                                    {{ $karyawan->pengalaman->jabatan2 ?? '-' }}</small>
                                <small class="text-muted">Masa Kerja:
                                    {{ $karyawan->pengalaman->masa_kerja2 ?? '-' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Referensi Group -->
                <div class="col-12">
                    <div class="section-title border-warning" style="background-color: #fff3cd;">
                        <i class="fas fa-handshake text-warning"></i> Referensi Internal Group
                    </div>
                    <div class="p-3 border border-warning rounded" style="background-color: #fff9e6;">
                        @if($karyawan->pengalaman->nama_pt_group)
                            <div class="row">
                                <div class="col-md-4"><strong>Nama PT:</strong><br>{{ $karyawan->pengalaman->nama_pt_group }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Departemen:</strong><br>{{ $karyawan->pengalaman->departemen_group }}
                                </div>
                                <div class="col-md-4"><strong>Jabatan:</strong><br>{{ $karyawan->pengalaman->jabatan_group }}
                                </div>
                            </div>
                        @else
                            <p class="mb-0 text-muted">Tidak ada referensi internal group</p>
                        @endif
                    </div>
                </div>

                <!-- CV & Sertifikat -->
                <div class="col-12">
                    <div class="section-title border-info" style="background-color: #e0f7fa;">
                        <i class="fas fa-file-pdf text-info"></i> CV (Curriculum Vitae) dan Sertifikat
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-1 fw-bold">CV (Curriculum Vitae):</p>
                            @if($karyawan->detail->cv)
                                <a href="{{ asset('public/storage/' . $karyawan->detail->cv) }}"
                                    class="btn btn-sm btn-outline-info w-100" target="_blank"><i class="fas fa-download"></i>
                                    Lihat CV</a>
                            @else
                                <span class="text-muted italic">Belum diupload</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 fw-bold">Sertifikat:</p>
                            @if($karyawan->detail->sertifikat)
                                <a href="{{ asset('public/storage/' . $karyawan->detail->sertifikat) }}"
                                    class="btn btn-sm btn-outline-info w-100" target="_blank"><i class="fas fa-download"></i>
                                    Lihat Sertifikat</a>
                            @else
                                <span class="text-muted italic">Belum diupload</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 fw-bold">Dokumen Lainnya:</p>
                            @if($karyawan->detail->dokumen_lain)
                                <a href="{{ asset('public/storage/' . $karyawan->detail->dokumen_lain) }}"
                                    class="btn btn-sm btn-outline-info w-100" target="_blank"><i class="fas fa-download"></i>
                                    Lihat Dokumen</a>
                            @else
                                <span class="text-muted italic">Belum diupload</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white text-center py-3">
            <button onclick="window.print()" class="btn btn-outline-dark btn-sm"><i class="fas fa-print"></i> Cetak
                Dokumen</button>
        </div>
    </div>

    <style>
        @media print {

            .navbar,
            .btn,
            footer {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .container-fluid {
                width: 100% !important;
                padding: 0 !important;
            }
        }
    </style>
@endsection