@extends('back.layouts.master')

@section('title', 'Haqqımızda Kart Bölməsi')

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
                    <h4 class="mb-sm-0">Haqqımızda Kart Bölməsi</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item active">Haqqımızda Kart Bölməsi</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="card-title">Haqqımızda Kart Bölməsi Məlumatları</h4>
                            @if($canCreate)
                                <a href="{{ route('back.pages.about-cart-sections.create') }}" class="btn btn-primary waves-effect waves-light">
                                    <i class="ri-add-line align-middle me-1"></i> Yeni
                                </a>
                            @endif
                        </div>

                        @if($aboutCartSection)
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
                                                    <th width="80">ID</th>
                                                    <th width="200">Şəkil</th>
                                                    <th>Ad (AZ)</th>
                                                    <th>Başlıq (AZ)</th>
                                                    <th>Təsvir (AZ)</th>
                                                    <th width="100">Status</th>
                                                    <th width="150">Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $aboutCartSection->id }}</td>
                                                    <td>
                                                        @if($aboutCartSection->image)
                                                            <img src="{{ asset($aboutCartSection->image) }}" alt="{{ $aboutCartSection->title_az }}" class="img-thumbnail" style="max-height: 100px">
                                                        @else
                                                            <span class="text-muted">Şəkil yoxdur</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $aboutCartSection->name_az }}</td>
                                                    <td>{{ $aboutCartSection->title_az }}</td>
                                                    <td>{{ \Illuminate\Support\Str::limit($aboutCartSection->description_az, 100) }}</td>
                                                    <td>
                                                        <div class="form-check form-switch form-switch-success mb-3" style="margin-bottom: 0 !important">
                                                            <input class="form-check-input status-switch" type="checkbox" role="switch" id="statusSwitch_{{ $aboutCartSection->id }}" data-id="{{ $aboutCartSection->id }}" {{ $aboutCartSection->status ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('back.pages.about-cart-sections.edit', $aboutCartSection->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Düzənlə">
                                                            <i class="mdi mdi-pencil font-size-16"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $aboutCartSection->id }}">
                                                            <i class="mdi mdi-trash-can font-size-16"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="en" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th width="80">ID</th>
                                                    <th width="200">Şəkil</th>
                                                    <th>Ad (EN)</th>
                                                    <th>Başlıq (EN)</th>
                                                    <th>Təsvir (EN)</th>
                                                    <th width="100">Status</th>
                                                    <th width="150">Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $aboutCartSection->id }}</td>
                                                    <td>
                                                        @if($aboutCartSection->image)
                                                            <img src="{{ asset($aboutCartSection->image) }}" alt="{{ $aboutCartSection->title_en }}" class="img-thumbnail" style="max-height: 100px">
                                                        @else
                                                            <span class="text-muted">Şəkil yoxdur</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $aboutCartSection->name_en ?? 'Tərcümə yoxdur' }}</td>
                                                    <td>{{ $aboutCartSection->title_en ?? 'Tərcümə yoxdur' }}</td>
                                                    <td>{{ $aboutCartSection->description_en ? \Illuminate\Support\Str::limit($aboutCartSection->description_en, 100) : 'Tərcümə yoxdur' }}</td>
                                                    <td>
                                                        <div class="form-check form-switch form-switch-success mb-3" style="margin-bottom: 0 !important">
                                                            <input class="form-check-input status-switch" type="checkbox" role="switch" id="statusSwitch_{{ $aboutCartSection->id }}_en" data-id="{{ $aboutCartSection->id }}" {{ $aboutCartSection->status ? 'checked' : '' }} disabled>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('back.pages.about-cart-sections.edit', $aboutCartSection->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Düzənlə">
                                                            <i class="mdi mdi-pencil font-size-16"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $aboutCartSection->id }}">
                                                            <i class="mdi mdi-trash-can font-size-16"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="ru" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th width="80">ID</th>
                                                    <th width="200">Şəkil</th>
                                                    <th>Ad (RU)</th>
                                                    <th>Başlıq (RU)</th>
                                                    <th>Təsvir (RU)</th>
                                                    <th width="100">Status</th>
                                                    <th width="150">Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $aboutCartSection->id }}</td>
                                                    <td>
                                                        @if($aboutCartSection->image)
                                                            <img src="{{ asset($aboutCartSection->image) }}" alt="{{ $aboutCartSection->title_ru }}" class="img-thumbnail" style="max-height: 100px">
                                                        @else
                                                            <span class="text-muted">Şəkil yoxdur</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $aboutCartSection->name_ru ?? 'Tərcümə yoxdur' }}</td>
                                                    <td>{{ $aboutCartSection->title_ru ?? 'Tərcümə yoxdur' }}</td>
                                                    <td>{{ $aboutCartSection->description_ru ? \Illuminate\Support\Str::limit($aboutCartSection->description_ru, 100) : 'Tərcümə yoxdur' }}</td>
                                                    <td>
                                                        <div class="form-check form-switch form-switch-success mb-3" style="margin-bottom: 0 !important">
                                                            <input class="form-check-input status-switch" type="checkbox" role="switch" id="statusSwitch_{{ $aboutCartSection->id }}_ru" data-id="{{ $aboutCartSection->id }}" {{ $aboutCartSection->status ? 'checked' : '' }} disabled>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('back.pages.about-cart-sections.edit', $aboutCartSection->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Düzənlə">
                                                            <i class="mdi mdi-pencil font-size-16"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $aboutCartSection->id }}">
                                                            <i class="mdi mdi-trash-can font-size-16"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Hələ heç bir məlumat əlavə edilməyib. Yeni məlumat əlavə etmək üçün "Yeni" düyməsini klikləyin.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silmə Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Təsdiq</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bu məlumatı silmək istədiyinizə əminsiniz?
            </div>
            <div class="modal-footer">
                <form action="" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bağla</button>
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        max-width: 100%;
        height: auto;
    }
</style>

@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Tooltipleri etkinleştir
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Silme işlemi
        $('.delete-btn').on('click', function() {
            var id = $(this).data('id');
            
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
                    $('#deleteForm').attr('action', '{{ route("back.pages.about-cart-sections.destroy", "") }}/' + id);
                    $('#deleteForm').submit();
                }
            });
        });
        
        // Durum değiştirme
        $('.status-switch').on('change', function() {
            var id = $(this).data('id');
            var csrfToken = '{{ csrf_token() }}';
            
            $.ajax({
                url: '{{ route("back.pages.about-cart-sections.toggle-status", "") }}/' + id,
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
    });
</script>
@endpush 