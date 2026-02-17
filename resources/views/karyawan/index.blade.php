@extends('layouts.app')

@section('actions')
    <a href="{{ route('karyawan.create') }}" class="btn btn-tambah btn-sm">
        <i class="fas fa-plus"></i> Tambah
    </a>
    <button class="btn btn-import btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fas fa-file-import"></i> Import
    </button>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="karyawanTable" class="table table-bordered table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No.</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Tempat, Tanggal Lahir</th>
                            <th>L/P</th>
                            <th>Alamat</th>
                            <th>Referensi</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawans as $index => $k)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    @if ($k->detail && $k->detail->foto)
                                        <img src="{{ asset('public/storage/' . $k->detail->foto) }}" width="40" height="40"
                                            class="rounded object-fit-cover shadow-sm">
                                    @else
                                        <div class="rounded bg-secondary d-flex align-items-center justify-content-center shadow-sm"
                                            style="width: 40px; height: 40px; margin: 0 auto;">
                                            <i class="fas fa-user text-white" style="font-size: 0.8rem;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $k->nama }}</td>
                                <td>
                                    {{ $k->detail->tempat_lahir ?? '-' }},
                                    {{ $k->detail->tanggal_lahir ? \Carbon\Carbon::parse($k->detail->tanggal_lahir)->format('d-m-Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ ($k->detail && $k->detail->jenis_kelamin == 'Laki-laki') ? 'L' : (($k->detail && $k->detail->jenis_kelamin == 'Perempuan') ? 'P' : '-') }}
                                </td>
                                <td style="max-width: 250px; font-size: 0.85rem;">
                                    {{ $k->detail->alamat_ktp ?? '-' }}
                                </td>
                                <td style="font-size: 0.85rem;">
                                    {{ $k->pengalaman->nama_pt_group ?? '-' }}
                                </td>
                                <td>{{ $k->no_hp }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $k->status == 'Bekerja' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $k->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('karyawan.show', $k->id) }}" class="btn btn-success btn-xs"
                                            title="Detail">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                        <a href="{{ route('karyawan.edit', $k->id) }}" class="btn btn-info btn-xs text-white"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('karyawan.destroy', $k->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">Belum ada data karyawan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg border-0 shadow-lg">
            <div class="modal-content border-0">
                <div class="modal-header text-white" style="background-color: #00bcd4; border-bottom: 3px solid #0097a7;">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="fas fa-file-excel me-2"></i> Import Data Karyawan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('karyawan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">1. Pilih File Excel</h6>
                            <div class="input-group">
                                <input type="file" name="file" class="form-control" id="importFile" accept=".xlsx, .xls"
                                    required>
                                <label class="input-group-text bg-light" for="importFile">Browse</label>
                            </div>
                            <div class="form-text mt-2 text-muted">Maksimal ukuran file 10MB.</div>
                        </div>

                        <div class="p-4 border rounded bg-white shadow-sm"
                            style="border-left: 5px solid #00bcd4 !important;">
                            <h6 class="fw-bold mb-3 text-secondary">2. Petunjuk & Ketentuan Import</h6>
                            <p class="text-muted small mb-3">Agar proses import berjalan lancar, <strong>WAJIB</strong>
                                menggunakan template yang disediakan:</p>

                            <a href="{{ route('karyawan.export-template') }}"
                                class="btn btn-success btn-sm mb-4 px-3 shadow-sm">
                                <i class="fas fa-download me-2"></i> Download Template Excel
                            </a>

                            <div class="p-3 bg-light rounded"
                                style="background-color: #fff9e6 !important; border: 1px solid #ffeeba;">
                                <ul class="small mb-0 text-dark" style="list-style-type: disc; padding-left: 1.5rem;">
                                    <li class="mb-1"><strong>Struktur Kolom:</strong> Jangan mengubah urutan kolom pada
                                        file template.</li>
                                    <li class="mb-1"><strong>Format Tanggal:</strong> Gunakan format <span
                                            class="text-danger">YYYY-MM-DD</span> (Tahun-Bulan-Tanggal) atau format Date
                                        Excel.</li>
                                    <li class="mb-1"><strong>NIK Unik:</strong> Data dengan NIK yang sudah ada di database
                                        akan <strong>dilewati (skip)</strong>.</li>
                                    <li class="mb-0"><strong>Data Mencakup:</strong>
                                        <ul class="mt-1" style="list-style-type: circle; padding-left: 1.2rem;">
                                            <li>Data Pribadi (NIK, Nama, TTL, Alamat, Kontak)</li>
                                            <li>Pendidikan (Formal & Informal)</li>
                                            <li>Keluarga (Status, Anak, Data Orang Tua)</li>
                                            <li>Riwayat Kerja (2 Pengalaman Terakhir)</li>
                                            <li>Lain-lain (Referensi, Catatan)</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-secondary px-4 py-2 shadow-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm"
                            style="background-color: #007bff;">
                            <i class="fas fa-upload me-2"></i> Import Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .btn-xs {
            padding: 2px 6px;
            font-size: 0.75rem;
        }

        table th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table td {
            font-size: 0.9rem;
        }

        /* Customizing DataTables Appearance */
        .dataTables_length select {
            padding-right: 30px !important;
        }

        div.dataTables_wrapper div.dataTables_info {
            font-size: 0.85rem;
            color: #666;
            padding-top: 15px;
        }

        div.dataTables_wrapper div.dataTables_paginate {
            padding-top: 15px;
        }

        .page-item.active .page-link {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Sembunyikan notifikasi error alert dari DataTables
            $.fn.dataTable.ext.errMode = 'none';

            $('#karyawanTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "search": "Cari:",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "emptyTable": "Belum ada data karyawan"
                },
                "lengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "Semua"]
                ],
                "order": [
                    [0, "asc"]
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [1, 9] // Foto and Aksi columns
                }]
            });
        });
    </script>
@endpush