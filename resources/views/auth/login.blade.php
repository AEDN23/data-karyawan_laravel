@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-4">
            <div class="card shadow-lg border-0">
                <div class="card-header-custom text-center py-4"
                    style="background-color: #17a2b8; border-radius: 8px 8px 0 0;">
                    <h4 class="mb-0 fw-bold text-white">
                        <i class="fas fa-user-lock me-2"></i> LOGIN SYSTEM
                    </h4>
                    <small class="text-white-50">Data Calon Karyawan</small>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger py-2 small">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email" name="email" class="form-control bg-light border-start-0"
                                    placeholder="Masukkan email" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" name="password" class="form-control bg-light border-start-0"
                                    placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-info text-white fw-bold py-2 shadow-sm">
                                LOGIN SEKARANG <i class="fas fa-sign-in-alt ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white border-0 text-center pb-4">
                    <p class="small text-muted mb-0">Lupa password? Hubungi Admin IT</p>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('karyawan.index') }}" class="text-decoration-none text-secondary small">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .input-group-text {
            border-right: none;
        }

        .form-control:focus {
            border-color: #17a2b8;
            box-shadow: none;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
        }

        .btn-info:hover {
            background-color: #138496;
        }
    </style>
@endsection