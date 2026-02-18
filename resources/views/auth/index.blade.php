@extends('layouts.app')

@section('content')
    <div class="card mb-4">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-users-cog me-2"></i> Manajemen User (Administrator)
            </div>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm shadow-sm">
                <i class="fas fa-plus me-1"></i> Tambah User
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Terdaftar Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $u)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td class="text-center">
                                    @if($u->role == 'superadmin')
                                        <span class="badge bg-danger">SUPERADMIN</span>
                                    @elseif($u->role == 'admin')
                                        <span class="badge bg-primary">ADMIN</span>
                                    @else
                                        <span class="badge bg-success">USER</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $u->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $u->id }}"
                                        data-name="{{ $u->name }}" data-email="{{ $u->email }}" data-role="{{ $u->role }}"
                                        data-bs-toggle="modal" data-bs-target="#editUserModal">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $u->id }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data user admin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background-color: #17a2b8;">
                    <h5 class="modal-title" id="editUserModalLabel">
                        <i class="fas fa-user-edit me-2"></i> Edit User Admin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="editUserForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Baru</label>
                            <input type="password" name="password" id="edit_password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                            <small class="text-muted" style="font-size: 0.75rem;">*Isi jika ingin mengganti password user ini.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <select name="role" id="edit_role" class="form-select" required>
                                <option value="user">USER</option>
                                <option value="admin">ADMIN</option>
                                <option value="superadmin">SUPERADMIN</option>
                            </select>
                        </div>
                        <div class="alert alert-info small py-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i> Perubahan email mungkin memerlukan verifikasi ulang
                            (jika diaktifkan).
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-secondary shadow-sm px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info text-white shadow-sm px-4">
                            <i class="fas fa-save me-1"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Hidden Form Delete -->
    <form id="deleteUserForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#userTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "search": "Cari:",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            // Handle Edit Button Click
            $('.btn-edit').on('click', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const email = $(this).data('email');
                const role = $(this).data('role');

                // Set form values
                $('#edit_name').val(name);
                $('#edit_email').val(email);
                $('#edit_role').val(role);

                // Update form action URL
                const actionUrl = "{{ url('/users') }}/" + id;
                $('#editUserForm').attr('action', actionUrl);
            });

            // Handle Delete Button Click
            $('.btn-delete').on('click', function () {
                const id = $(this).data('id');
                const actionUrl = "{{ url('/users') }}/" + id;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data user yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#deleteUserForm').attr('action', actionUrl);
                        $('#deleteUserForm').submit();
                    }
                });
            });
        });
    </script>
@endpush