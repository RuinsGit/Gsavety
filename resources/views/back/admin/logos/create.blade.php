@extends('back.layouts.master')

@section('title', 'Yeni Logo Əlavə Et')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Yeni Logo Əlavə Et</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.logos.index') }}">Logolar</a></li>
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

                        @if($logoCount >= 1)
                            <div class="alert alert-danger">
                                Zaten bir logo mevcut. Yeni bir logo ekleyemezsiniz.
                            </div>
                        @else
                            <form action="{{ route('back.pages.logos.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-justified" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#az" role="tab">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span class="d-none d-sm-block">AZ</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#en" role="tab">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span class="d-none d-sm-block">EN</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#ru" role="tab">
                                            <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                            <span class="d-none d-sm-block">RU</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content p-3 text-muted">
                                    <!-- Az tab -->
                                    <div class="tab-pane active" id="az" role="tabpanel">
                                        <!-- Logo 1 -->
                                        <div class="mb-3">
                                            <label for="logo_1_image" class="form-label">Logo 1 Şəkli:</label>
                                            <input type="file" class="form-control" name="logo_1_image" accept=".jpeg,.png,.jpg,.gif,.svg" required>
                                            @error('logo_1_image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="logo_title1_az" class="form-label">Header logo Title (AZ):</label>
                                            <input type="text" class="form-control" name="logo_title1_az" value="{{ old('logo_title1_az') }}" required>
                                            @error('logo_title1_az')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="logo_alt1_az" class="form-label">Header logo ALT (AZ):</label>
                                            <textarea class="form-control" name="logo_alt1_az" rows="4" required>{{ old('logo_alt1_az') }}</textarea>
                                            @error('logo_alt1_az')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Logo 2 -->
                                        <div class="mb-3">
                                            <label for="logo_2_image" class="form-label">Logo 2 Şəkli:</label>
                                            <input type="file" class="form-control" name="logo_2_image" accept=".jpeg,.png,.jpg,.gif,.svg" required>
                                            @error('logo_2_image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="logo_title2_az" class="form-label">Fotter logo Title (AZ):</label>
                                            <input type="text" class="form-control" name="logo_title2_az" value="{{ old('logo_title2_az') }}" required>
                                            @error('logo_title2_az')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="logo_alt2_az" class="form-label">Fotter logo ALT (AZ):</label>
                                            <textarea class="form-control" name="logo_alt2_az" rows="4" required>{{ old('logo_alt2_az') }}</textarea>
                                            @error('logo_alt2_az')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- En tab -->
                                    <div class="tab-pane" id="en" role="tabpanel">
                                        <!-- Logo 1 -->
                                        <div class="mb-3">
                                            <label for="logo_alt1_en" class="form-label">Header logo ALT (EN):</label>
                                            <textarea class="form-control" name="logo_alt1_en" rows="4" required>{{ old('logo_alt1_en') }}</textarea>
                                            @error('logo_alt1_en')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="logo_title1_en" class="form-label">Header logo Title (EN):</label>
                                            <input type="text" class="form-control" name="logo_title1_en" value="{{ old('logo_title1_en') }}" required>
                                            @error('logo_title1_en')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="logo_alt2_en" class="form-label">Fotter logo ALT (EN):</label>
                                            <textarea class="form-control" name="logo_alt2_en" rows="4" required>{{ old('logo_alt2_en') }}</textarea>
                                            @error('logo_alt2_en')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="logo_title2_en" class="form-label">Fotter logo Title (EN):</label>
                                            <input type="text" class="form-control" name="logo_title2_en" value="{{ old('logo_title2_en') }}" required>
                                            @error('logo_title2_en')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Ru tab -->
                                    <div class="tab-pane" id="ru" role="tabpanel">
                                        <!-- Logo 1 -->
                                        <div class="mb-3">
                                            <label for="logo_alt1_ru" class="form-label">Header logo ALT (RU):</label>
                                            <textarea class="form-control" name="logo_alt1_ru" rows="4" required>{{ old('logo_alt1_ru') }}</textarea>
                                            @error('logo_alt1_ru')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="logo_title1_ru" class="form-label">Header logo Title (RU):</label>
                                            <input type="text" class="form-control" name="logo_title1_ru" value="{{ old('logo_title1_ru') }}" required>
                                            @error('logo_title1_ru')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="logo_alt2_ru" class="form-label">Fotter logo ALT (RU):</label>
                                            <textarea class="form-control" name="logo_alt2_ru" rows="4" required>{{ old('logo_alt2_ru') }}</textarea>
                                            @error('logo_alt2_ru')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="logo_title2_ru" class="form-label">Fotter logo Title (RU):</label>
                                            <input type="text" class="form-control" name="logo_title2_ru" value="{{ old('logo_title2_ru') }}" required>
                                            @error('logo_title2_ru')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Yadda saxla</button>
                                        <a href="{{ route('back.pages.logos.index') }}" class="btn btn-secondary">Ləğv et</a>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 