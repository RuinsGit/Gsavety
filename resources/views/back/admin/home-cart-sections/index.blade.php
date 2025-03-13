@extends('back.layouts.master')

@section('title', 'Kart Bölməsi')

@section('content')
    <style>
        .swal2-popup {
            border-radius: 50px;
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
                    confirmButtonText: 'Yaxşı',
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
                    confirmButtonText: 'Yaxşı',
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
                        <h4 class="mb-sm-0">Kart Bölməsi</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                                <li class="breadcrumb-item active">Kart Bölməsi</li>
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
                                @if($canCreate)
                                    <a href="{{ route('back.pages.home-cart-sections.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Yeni Kart Əlavə Et
                                    </a>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-plus"></i> Yeni Kart Əlavə Et
                                    </button>
                                    <small class="ms-2 text-muted">Maksimum 1 ədəd kart əlavə edilə bilər.</small>
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
                                                    <th>Şəkil</th>
                                                    <th>Başlıq (AZ)</th>
                                                    <th>Açıqlama (AZ)</th>
                                                    <th>Sıra</th>
                                                    <th>Status</th>
                                                    <th>Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($homeCartSections as $section)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                @if($section->image)
                                                                    <img src="{{ asset($section->image) }}" alt="{{ $section->title_az }}" class="section-img">
                                                                @else
                                                                    <span class="badge bg-danger">Şəkil Yoxdur</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $section->title_az }}</td>
                                                        <td>{{ Str::limit($section->description_az, 30) }}</td>
                                                        <td>{{ $section->order }}</td>
                                                        <td>
                                                            <div class="form-check form-switch form-switch-success">
                                                                <form action="{{ route('back.pages.home-cart-sections.toggle-status', $section->id) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-check-input toggle-status" type="checkbox" role="switch" id="status_{{ $section->id }}" {{ $section->status ? 'checked' : '' }} data-id="{{ $section->id }}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('back.pages.home-cart-sections.edit', $section->id) }}" class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form id="delete-form-{{ $section->id }}" action="{{ route('back.pages.home-cart-sections.destroy', $section->id) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteData({{ $section->id }})">
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
                                                    <th>Şəkil</th>
                                                    <th>Başlıq (EN)</th>
                                                    <th>Açıqlama (EN)</th>
                                                    <th>Sıra</th>
                                                    <th>Status</th>
                                                    <th>Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($homeCartSections as $section)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                @if($section->image)
                                                                    <img src="{{ asset($section->image) }}" alt="{{ $section->title_en }}" class="section-img">
                                                                @else
                                                                    <span class="badge bg-danger">Şəkil Yoxdur</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $section->title_en }}</td>
                                                        <td>{{ Str::limit($section->description_en, 30) }}</td>
                                                        <td>{{ $section->order }}</td>
                                                        <td>
                                                            <div class="form-check form-switch form-switch-success">
                                                                <form action="{{ route('back.pages.home-cart-sections.toggle-status', $section->id) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-check-input toggle-status" type="checkbox" role="switch" id="status_en_{{ $section->id }}" {{ $section->status ? 'checked' : '' }} data-id="{{ $section->id }}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('back.pages.home-cart-sections.edit', $section->id) }}" class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form id="delete-form-en-{{ $section->id }}" action="{{ route('back.pages.home-cart-sections.destroy', $section->id) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteData({{ $section->id }})">
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
                                                    <th>Şəkil</th>
                                                    <th>Başlıq (RU)</th>
                                                    <th>Açıqlama (RU)</th>
                                                    <th>Sıra</th>
                                                    <th>Status</th>
                                                    <th>Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($homeCartSections as $section)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                @if($section->image)
                                                                    <img src="{{ asset($section->image) }}" alt="{{ $section->title_ru }}" class="section-img">
                                                                @else
                                                                    <span class="badge bg-danger">Şəkil Yoxdur</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $section->title_ru }}</td>
                                                        <td>{{ Str::limit($section->description_ru, 30) }}</td>
                                                        <td>{{ $section->order }}</td>
                                                        <td>
                                                            <div class="form-check form-switch form-switch-success">
                                                                <form action="{{ route('back.pages.home-cart-sections.toggle-status', $section->id) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-check-input toggle-status" type="checkbox" role="switch" id="status_ru_{{ $section->id }}" {{ $section->status ? 'checked' : '' }} data-id="{{ $section->id }}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('back.pages.home-cart-sections.edit', $section->id) }}" class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form id="delete-form-ru-{{ $section->id }}" action="{{ route('back.pages.home-cart-sections.destroy', $section->id) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteData({{ $section->id }})">
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

    .section-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.3s ease;
    }

    .image-preview:hover .section-img {
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
    </style>
@endsection

@push('js')
<script>
    function deleteData(id) {
        Swal.fire({
            title: 'Silmək istədiyinizdən əminsiniz?',
            text: "Bu əməliyyat geri qaytarıla bilməz!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Bəli, sil!',
            cancelButtonText: 'Ləğv et'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    $(document).ready(function() {
        $('.toggle-status').change(function() {
            var id = $(this).data('id');
            var csrfToken = '{{ csrf_token() }}';
            
            $.ajax({
                url: '{{ route("back.pages.home-cart-sections.toggle-status", "") }}/' + id,
                type: 'POST',
                data: {
                    _token: csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Status uğurla dəyişdirildi",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Xəta baş verdi",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Xəta baş verdi",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Toggle'ı eski durumuna geri getir
                    $(this).prop('checked', !$(this).prop('checked'));
                }
            });
        });

        $('.table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Azerbaijani.json"
            }
        });
    });
</script>
@endpush 