@extends('back.layouts.master')

@section('title', 'Logolar')

@section('content')
    <style>
        .swal2-popup {
            border-radius: 50px; /* Modern görünüm için köşe yuvarlama */
        }
    </style>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "{{ session('success') }}",
                    showConfirmButton: true,
                    confirmButtonText: 'Tamam',
                    timer: 1500
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "{{ session('error') }}",
                    showConfirmButton: true,
                    confirmButtonText: 'Tamam',
                    timer: 1500
                });
            });
        </script>
    @endif

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Logolar</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Səhifə</a></li>
                                <li class="breadcrumb-item active">Logolar</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12 d-flex justify-content-end mb-4">
                                @if($logoCount === 0)
                                    <a href="{{ route('back.pages.logos.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Yeni
                                    </a>
                                @else
                                    <div class="alert alert-warning">
                                        Hal hazırda logo mövcuddur. yeni logo yaratmaq üçün əvvəlcə əvvəlcəki logo'nu silin. ya da redaktə edin.
                                    </div>
                                @endif
                            </div>

                            <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#az" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block" style=" color: #ff8a33;">AZ</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#en" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block" style=" color: #ff8a33;">EN</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#ru" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block" style=" color: #ff8a33;">RU</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="az" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Header logo Şəkli</th>
                                                    <th>Header logo Başlığı (AZ)</th>
                                                    <th>Header logo ALT (AZ)</th>
                                                    <th>Fotter logo Şəkli</th>
                                                    <th>Fotter logo Başlığı (AZ)</th>
                                                    <th>Fotter logo ALT (AZ)</th>
                                                    <th>Status</th>
                                                    <th>Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($logos as $logo)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                <img src="{{ asset($logo->logo_1_image) }}" alt="Logo 1" class="logo-img">
                                                            </div>
                                                        </td>
                                                        <td>{{ $logo->logo_title1_az }}</td>
                                                        <td>{{ $logo->logo_alt1_az }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                <img src="{{ asset($logo->logo_2_image) }}" alt="Logo 2" class="logo-img">
                                                            </div>
                                                        </td>
                                                        <td>{{ $logo->logo_title2_az }}</td>
                                                        <td>{{ $logo->logo_alt2_az }}</td>
                                                        <td>
                                                            {{ $logo->status ?? 1 == 1 ? 'Aktif' : 'Deaktif' }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('back.pages.logos.edit', $logo->id) }}" class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form id="delete-form-{{ $logo->id }}" action="{{ route('back.pages.logos.destroy', $logo->id) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteData({{ $logo->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="en" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Header logo Şəkli</th>
                                                    <th>Header logo Başlığı (EN)</th>
                                                    <th>Header logo ALT (EN)</th>
                                                    <th>Fotter logo Şəkli</th>
                                                    <th>Fotter logo Başlığı (EN)</th>
                                                    <th>Fotter logo ALT (EN)</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($logos as $logo)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                <img src="{{ asset($logo->logo_1_image) }}" alt="Logo 1" class="logo-img">
                                                            </div>
                                                        </td>
                                                        <td>{{ $logo->logo_title1_en }}</td>
                                                        <td>{{ $logo->logo_alt1_en }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                <img src="{{ asset($logo->logo_2_image) }}" alt="Logo 2" class="logo-img">
                                                            </div>
                                                        </td>
                                                        <td>{{ $logo->logo_title2_en }}</td>
                                                        <td>{{ $logo->logo_alt2_en }}</td>
                                                        <td>
                                                            {{ $logo->status ?? 1 == 1 ? 'Aktif' : 'Deaktif' }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('back.pages.logos.edit', $logo->id) }}" class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form id="delete-form-{{ $logo->id }}" action="{{ route('back.pages.logos.destroy', $logo->id) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteData({{ $logo->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="ru" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Header logo Şəkli</th>
                                                    <th>Header logo Başlığı (RU)</th>
                                                    <th>Header logo ALT (RU)</th>
                                                    <th>Fotter logo Şəkli</th>
                                                    <th>Fotter logo Başlığı (RU)</th>
                                                    <th>Fotter logo ALT (RU)</th>
                                                    <th>Status</th>
                                                    <th>Действия</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($logos as $logo)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                <img src="{{ asset($logo->logo_1_image) }}" alt="Logo 1" class="logo-img">
                                                            </div>
                                                        </td>
                                                        <td>{{ $logo->logo_title1_ru }}</td>
                                                        <td>{{ $logo->logo_alt1_ru }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                <img src="{{ asset($logo->logo_2_image) }}" alt="Logo 2" class="logo-img">
                                                            </div>
                                                        </td>
                                                        <td>{{ $logo->logo_title2_ru }}</td>
                                                        <td>{{ $logo->logo_alt2_ru }}</td>
                                                        <td>
                                                            {{ $logo->status ?? 1 == 1 ? 'Aktif' : 'Deaktif' }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('back.pages.logos.edit', $logo->id) }}" class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form id="delete-form-{{ $logo->id }}" action="{{ route('back.pages.logos.destroy', $logo->id) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteData({{ $logo->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .image-preview {
        width: 100px;
        height: 100px;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 0 auto;
    }

    .logo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.3s ease;
    }

    .image-preview:hover .logo-img {
        transform: scale(1.05);
    }

    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        border-radius: 12px;
        overflow: hidden;
    }

    .nav-tabs {
        border-bottom: 2px solid #eee;
        margin-bottom: 20px;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
        transition: all 0.2s ease;
    }

    .nav-tabs .nav-link.active {
        color: #2c3e50;
        border-bottom: 2px solid #3498db;
        background: transparent;
    }

    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #3498db;
    }

    .form-label {
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 6px;
        border: 1px solid #dee2e6;
        padding: 8px 12px;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.2s ease;
        border-radius: 6px;
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-secondary {
        background-color: #95a5a6;
        border-color: #95a5a6;
    }

    .btn-secondary:hover {
        background-color: #7f8c8d;
        border-color: #7f8c8d;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .tab-content {
        padding: 20px;
        background-color: #fff;
        border-radius: 0 0 8px 8px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .image-preview {
            width: 80px;
            height: 80px;
        }
        
        .nav-tabs .nav-link {
            padding: 8px 12px;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }
    }
    </style>
@endsection

@push('js')
<script>
    function deleteData(id) {
        Swal.fire({
            title: 'Silmək istədiyinizdən əminsiniz?',
            text: "Bu əməliyyat geri alına bilməz!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Bəli, sil!',
            cancelButtonText: 'Xeyr'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    $(document).ready(function() {
        $('.table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
            }
        });
    });
</script>
@endpush 