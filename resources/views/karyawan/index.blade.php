@extends('layouts.app')

@section('actions')
    <a href="{{ route('karyawan.create') }}" class="btn btn-tambah btn-sm">
        <i class="fas fa-plus"></i> Tambah
    </a>
    <button class="btn btn-import btn-sm">
        <i class="fas fa-file-import"></i> Import
    </button>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span>Tampilkan</span>
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <span>data</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span>Cari:</span>
                    <input type="text" class="form-control form-control-sm">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No.</th>
                            <th>Foto</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Tempat, Tanggal Lahir</th>
                            <th>L/P</th>
                            <th>Alamat</th>
                            <th>Referensi</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawans as $index => $k)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    @if($k->detail && $k->detail->foto)
                                        <img src="{{ asset('public/storage/' . $k->detail->foto) }}" width="40" height="40"
                                            class="rounded object-fit-cover shadow-sm">
                                    @else
                                        <img src="https://via.placeholder.com/40" width="40" height="40" class="rounded">
                                    @endif
                                </td>
                                <td>{{ $k->nik }}</td>
                                <td class="fw-bold">{{ $k->nama }}</td>
                                <td>
                                    {{ $k->detail->tempat_lahir ?? '-' }},
                                    {{ $k->detail->tanggal_lahir ? \Carbon\Carbon::parse($k->detail->tanggal_lahir)->format('d-m-Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ ($k->detail && $k->detail->jenis_kelamin == 'Laki-laki') ? 'L' : (($k->detail && $k->detail->jenis_kelamin == 'Perempuan') ? 'P' : '-') }}
                                </td>
                                <td style="max-width: 250px; font-size: 0.85rem;">
                                    {{ $k->detail->alamat ?? '-' }}
                                </td>
                                <td style="font-size: 0.85rem;">
                                    {{ $k->pengalaman->nama_pt_group ?? '-' }}
                                </td>
                                <td>{{ $k->no_hp }}</td>
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
                                <td colspan="10" class="text-center py-4 text-muted">Belum ada data karyawan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">Menampilkan {{ $karyawans->count() }} data</small>
                {{-- Pagination akan muncul di sini jika menggunakan paginate() --}}
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
    </style>
@endsection