@extends('back.layouts.master')

@section('title', 'Yeni Featured Box')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Yeni Featured Box</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('back.pages.home-featured-boxes.index') }}">Featured Boxlar</a>
                                </li>
                                <li class="breadcrumb-item active">Yeni</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('back.pages.home-featured-boxes.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                            
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
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="title_az" class="form-label">Başlıq (AZ) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('title_az') is-invalid @enderror" id="title_az" name="title_az" value="{{ old('title_az') }}" required>
                                                @error('title_az')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="en" role="tabpanel">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="title_en" class="form-label">Başlıq (EN)</label>
                                                <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en') }}">
                                                @error('title_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="ru" role="tabpanel">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="title_ru" class="form-label">Başlıq (RU)</label>
                                                <input type="text" class="form-control @error('title_ru') is-invalid @enderror" id="title_ru" name="title_ru" value="{{ old('title_ru') }}">
                                                @error('title_ru')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label for="image" class="form-label">Şəkil <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" required>
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Tövsiyə edilən ölçü: 80x80px, Maksimum 2MB</small>
                                        </div>
                                        <div class="col-md-2 mt-4">
                                            <div class="image-preview-container" style="display: none;">
                                                <img id="preview-image" src="#" alt="Önizləmə" class="img-fluid rounded" style="max-height: 200px;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label for="order" class="form-label">Sıra</label>
                                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0">
                                            @error('order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input" type="checkbox" id="status" name="status" checked>
                                                <label class="form-check-label" for="status">Aktiv</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12 text-end">
                                            <button type="submit" class="btn btn-primary">Yadda saxla</button>
                                            <a href="{{ route('back.pages.home-featured-boxes.index') }}" class="btn btn-secondary">Ləğv et</a>
                                        </div>
                                    </div>
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
<script>
    $(document).ready(function() {
        // Image preview
        $("#image").change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#preview-image").attr("src", event.target.result);
                    $(".image-preview-container").show();
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush 