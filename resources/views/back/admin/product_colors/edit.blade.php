@extends('back.layouts.master')

@section('title', 'Məhsul Rəngi Redaktə Et')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Məhsul Rəngi Redaktə Et</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.product_colors.index') }}">Məhsul Rəngləri</a></li>
                            <li class="breadcrumb-item active">Redaktə</li>
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

                        <form action="{{ route('back.pages.product_colors.update', $color->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="product_id" class="form-label">Məhsul <span class="text-danger">*</span></label>
                                    <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                        <option value="">Məhsul seçin</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id', $color->product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

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
                                    <div class="mb-3">
                                        <label for="color_name_az" class="form-label">Rəng Adı (AZ) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('color_name_az') is-invalid @enderror" id="color_name_az" name="color_name_az" value="{{ old('color_name_az', $color->color_name_az) }}" required>
                                        @error('color_name_az')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- En tab -->
                                <div class="tab-pane" id="en" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="color_name_en" class="form-label">Rəng Adı (EN) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('color_name_en') is-invalid @enderror" id="color_name_en" name="color_name_en" value="{{ old('color_name_en', $color->color_name_en) }}" required>
                                        @error('color_name_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Ru tab -->
                                <div class="tab-pane" id="ru" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="color_name_ru" class="form-label">Rəng Adı (RU) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('color_name_ru') is-invalid @enderror" id="color_name_ru" name="color_name_ru" value="{{ old('color_name_ru', $color->color_name_ru) }}" required>
                                        @error('color_name_ru')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="color_code" class="form-label">Rəng Kodu (HEX)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('color_code') is-invalid @enderror" id="color_code" name="color_code" value="{{ old('color_code', $color->color_code) }}" placeholder="#FFFFFF">
                                        <input type="color" class="form-control form-control-color" id="color_picker" value="{{ old('color_code', $color->color_code ?? '#FFFFFF') }}" title="Rəng seçin">
                                    </div>
                                    @error('color_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- <div class="col-md-6">
                                    <label for="color_image" class="form-label">Rəng Şəkli</label>
                                    @if($color->color_image)
                                        <div class="mb-2">
                                            <img src="{{ asset($color->color_image) }}" alt="{{ $color->color_name_az }}" width="50" class="img-thumbnail">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('color_image') is-invalid @enderror" id="color_image" name="color_image">
                                    @error('color_image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div> -->
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="sort_order" class="form-label">Sıralama</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $color->sort_order) }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch form-switch-success mt-4">
                                        <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{ old('status', $color->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Aktiv</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">Yadda saxla</button>
                                    <a href="{{ route('back.pages.product_colors.index') }}" class="btn btn-secondary">Ləğv et</a>
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
        
        $('#color_picker').change(function() {
            $('#color_code').val($(this).val());
        });

        $('#color_code').change(function() {
            $('#color_picker').val($(this).val());
        });
    });
</script>
@endpush 