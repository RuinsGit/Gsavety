@extends('back.layouts.master')

@section('title', 'Yeni Partner Hero Əlavə Et')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Yeni Partner Hero Əlavə Et</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.partner-heroes.index') }}">Partner Hero</a></li>
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
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('back.pages.partner-heroes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Ana Sekmeler -->
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-4" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#partner_hero_info" role="tab" style="color:rgb(0, 0, 0);">
                                        <i class="ri-list-check-2 me-1 align-middle"></i> Partner Hero Məlumatları
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#language_info" role="tab" style="color:rgb(0, 0, 0);">
                                        <i class="ri-translate-2 me-1 align-middle"></i> Dil Məlumatları
                                    </a>
                                </li>
                            </ul>

                            <!-- Ana Sekme İçerikleri -->
                            <div class="tab-content p-3">
                                <!-- Partner Hero Məlumatları Sekmesi -->
                                <div class="tab-pane active" id="partner_hero_info" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Əsas Məlumatlar</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="order" class="form-label">Sıra</label>
                                                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}">
                                                        @error('order')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Partner Hero Şəkli</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="image" class="form-label">Şəkil <span class="text-danger">*</span></label>
                                                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" required>
                                                        <div class="mt-2 text-muted small">Tövsiyə olunan ölçü: 1920x800px</div>
                                                        @error('image')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div id="image-preview" class="mt-3 d-none">
                                                        <img src="" alt="Önizləmə" class="img-thumbnail" style="max-height: 200px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Status</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-check form-switch form-switch-success mb-3">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{ old('status', 1) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="status">Aktiv</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dil Məlumatları Sekmesi -->
                                <div class="tab-pane" id="language_info" role="tabpanel">
                                    <!-- Dil Sekmeleri -->
                                    <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#lang_az" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">Azərbaycan</span>
                                            </a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-bs-toggle="tab" href="#lang_en" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                <span class="d-none d-sm-block">İngilis</span>
                                            </a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-bs-toggle="tab" href="#lang_ru" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                                <span class="d-none d-sm-block">Rus</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- Dil Sekme İçerikleri -->
                                    <div class="tab-content p-3 text-muted">
                                        <!-- Az tab -->
                                        <div class="tab-pane active" id="lang_az" role="tabpanel">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Azərbaycan Dili Məlumatları</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="title_az" class="form-label">Başlıq (AZ) <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('title_az') is-invalid @enderror" id="title_az" name="title_az" value="{{ old('title_az') }}" required>
                                                        @error('title_az')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description_az" class="form-label">Açıqlama (AZ) <span class="text-danger">*</span></label>
                                                        <textarea class="form-control @error('description_az') is-invalid @enderror" id="description_az" name="description_az" rows="4" required>{{ old('description_az') }}</textarea>
                                                        @error('description_az')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- En tab -->
                                        <div class="tab-pane" id="lang_en" role="tabpanel">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">İngilis Dili Məlumatları</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="title_en" class="form-label">Başlıq (EN)</label>
                                                        <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en') }}">
                                                        @error('title_en')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description_en" class="form-label">Açıqlama (EN)</label>
                                                        <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="4">{{ old('description_en') }}</textarea>
                                                        @error('description_en')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ru tab -->
                                        <div class="tab-pane" id="lang_ru" role="tabpanel">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Rus Dili Məlumatları</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="title_ru" class="form-label">Başlıq (RU)</label>
                                                        <input type="text" class="form-control @error('title_ru') is-invalid @enderror" id="title_ru" name="title_ru" value="{{ old('title_ru') }}">
                                                        @error('title_ru')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description_ru" class="form-label">Açıqlama (RU)</label>
                                                        <textarea class="form-control @error('description_ru') is-invalid @enderror" id="description_ru" name="description_ru" rows="4">{{ old('description_ru') }}</textarea>
                                                        @error('description_ru')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line align-bottom me-1"></i> Yadda saxla
                                    </button>
                                    <a href="{{ route('back.pages.partner-heroes.index') }}" class="btn btn-secondary">
                                        <i class="ri-close-line align-bottom me-1"></i> Ləğv et
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs-custom .nav-item .nav-link {
        position: relative;
        padding: 15px 20px;
        border: 0;
        color: #495057;
        font-weight: 500;
        border-radius: 4px 4px 0 0;
        transition: all 0.3s;
    }
    
    .nav-tabs-custom .nav-item .nav-link.active {
        color: #3498db;
        background-color: #f8f9fa;
        border-bottom: 2px solid #3498db;
    }
    
    .nav-tabs-custom .nav-item .nav-link:hover:not(.active) {
        color: #3498db;
        background-color: rgba(52, 152, 219, 0.1);
    }
    
    .nav-pills .nav-link.active {
        background-color: #3498db;
    }
    
    .card-header {
        padding: 12px 20px;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
</style>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Şəkil önizləmə
        $('#image').change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').removeClass('d-none');
                    $('#image-preview img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush 