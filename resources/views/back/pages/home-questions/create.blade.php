@extends('back.layouts.master')

@section('title', 'Yeni Sual')

@section('content')
<style>
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
        border-radius: 15px;
        background: #fff;
    }

    .card-body {
        padding: 2rem;
    }

    /* Nav Tabs */
    .nav-tabs {
        border: none;
        background: #f8f9fa;
        padding: 0.5rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .nav-tabs .nav-link {
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        color: #2196F3;
    }

    .nav-tabs .nav-link.active {
        background: linear-gradient(45deg, #2196F3, #1976D2);
        color: white;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    }

    /* Form Controls */
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    /* Buttons */
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(45deg, #2196F3, #1976D2);
        border: none;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(33, 150, 243, 0.4);
    }

    .btn-secondary {
        background: #f8f9fa;
        border: none;
        color: #6c757d;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        color: #495057;
    }

    /* Text Editor */
    .ck-editor__editable {
        min-height: 200px;
        border-radius: 0 0 10px 10px !important;
    }

    .ck.ck-editor__main>.ck-editor__editable {
        background-color: #fff;
    }

    .ck.ck-toolbar {
        border-radius: 10px 10px 0 0 !important;
        background-color: #f8f9fa;
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

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Yeni Sual</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.home-questions.index') }}">Suallar</a></li>
                            <li class="breadcrumb-item active">Yeni</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('back.pages.home-questions.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#az" role="tab">
                                        <i class="fas fa-language me-2"></i>AZ
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#en" role="tab">
                                        <i class="fas fa-language me-2"></i>EN
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#ru" role="tab">
                                        <i class="fas fa-language me-2"></i>RU
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab content -->
                            <div class="tab-content p-3">
                                <!-- AZ Tab -->
                                <div class="tab-pane fade show active" id="az" role="tabpanel">
                                    <div class="mb-4">
                                        <label class="form-label" for="title_az">
                                            <i class="fas fa-heading me-2"></i>Başlıq (AZ)
                                        </label>
                                        <input type="text" class="form-control @error('title_az') is-invalid @enderror" 
                                               id="title_az" name="title_az" value="{{ old('title_az') }}" required>
                                        @error('title_az')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="description_az">
                                            <i class="fas fa-align-left me-2"></i>Mətn (AZ)
                                        </label>
                                        <textarea class="form-control @error('description_az') is-invalid @enderror" 
                                                  id="description_az" name="description_az" rows="5" required>{{ old('description_az') }}</textarea>
                                        @error('description_az')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- EN Tab -->
                                <div class="tab-pane fade" id="en" role="tabpanel">
                                    <div class="mb-4">
                                        <label class="form-label" for="title_en">
                                            <i class="fas fa-heading me-2"></i>Title (EN)
                                        </label>
                                        <input type="text" class="form-control @error('title_en') is-invalid @enderror" 
                                               id="title_en" name="title_en" value="{{ old('title_en') }}" required>
                                        @error('title_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="description_en">
                                            <i class="fas fa-align-left me-2"></i>Text (EN)
                                        </label>
                                        <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                                  id="description_en" name="description_en" rows="5" required>{{ old('description_en') }}</textarea>
                                        @error('description_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- RU Tab -->
                                <div class="tab-pane fade" id="ru" role="tabpanel">
                                    <div class="mb-4">
                                        <label class="form-label" for="title_ru">
                                            <i class="fas fa-heading me-2"></i>Заголовок (RU)
                                        </label>
                                        <input type="text" class="form-control @error('title_ru') is-invalid @enderror" 
                                               id="title_ru" name="title_ru" value="{{ old('title_ru') }}" required>
                                        @error('title_ru')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="description_ru">
                                            <i class="fas fa-align-left me-2"></i>Текст (RU)
                                        </label>
                                        <textarea class="form-control @error('description_ru') is-invalid @enderror" 
                                                  id="description_ru" name="description_ru" rows="5" required>{{ old('description_ru') }}</textarea>
                                        @error('description_ru')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-2"></i>Yadda saxla
                                </button>
                                <a href="{{ route('back.pages.home-questions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>İmtina
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Form Validation
        const form = document.querySelector('.needs-validation');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);

        // Form Submit Confirmation
        $('form').on('submit', function(e) {
            if (!form.checkValidity()) return;

            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Əminsiniz?',
                text: 'Məlumatları yadda saxlamaq istədiyinizə əminsiniz?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Bəli',
                cancelButtonText: 'Xeyr',
                customClass: {
                    confirmButton: 'btn btn-primary me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush 