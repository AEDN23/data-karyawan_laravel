<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Calon Karyawan</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .navbar-custom {
            background-color: #fff;
            border-bottom: 2px solid #17a2b8;
            padding: 1rem 2rem;
        }

        .navbar-brand {
            font-weight: 700;
            color: #17a2b8 !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card-header-custom {
            background-color: #17a2b8;
            color: white;
            border-radius: 8px 8px 0 0 !important;
            padding: 12px 20px;
            font-weight: 600;
        }

        .btn-tambah {
            background-color: #fff;
            color: #17a2b8;
            border: 1px solid #17a2b8;
        }

        .btn-tambah:hover {
            background-color: #17a2b8;
            color: #fff;
        }

        .btn-import {
            background-color: #fff;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .btn-import:hover {
            background-color: #dc3545;
            color: #fff;
        }

        footer {
            margin-top: 50px;
            padding: 20px 0;
            background-color: #fff;
            text-align: center;
            font-size: 0.85rem;
            color: #666;
            border-top: 1px solid #eee;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #555;
        }

        .text-required {
            color: red;
        }

        .section-title {
            background-color: #e9ecef;
            padding: 8px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-custom sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('karyawan.index') }}">
                <i class="fas fa-user-circle"></i> Data Calon Karyawan
            </a>
            <div class="d-flex gap-2">
                @yield('actions')
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer>
        © 2025 · <a href="http://www.elastormix.co.id" target="_blank" class="text-decoration-none"
            style="color:#17a2b8">www.elastormix.co.id</a>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Notifikasi Sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        // Notifikasi Error Umum
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
            });
        @endif

        // Notifikasi Error Validasi
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Input!',
                html: `
                        <ul class="text-start">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
            });
        @endif
    </script>
</body>

</html>