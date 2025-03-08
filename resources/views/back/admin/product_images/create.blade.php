@extends('back.layouts.master')

@section('title', 'Yeni Məhsul Şəkli Əlavə Et')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Yeni Məhsul Şəkli Əlavə Et</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.product_images.index') }}">Məhsul Şəkilləri</a></li>
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

                        <form action="{{ route('back.pages.product_images.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="product_id" class="form-label">Məhsul <span class="text-danger">*</span></label>
                                    <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                        <option value="">Məhsul seçin</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="product_color_id" class="form-label">Rəng</label>
                                    <select class="form-select" id="product_color_id" name="product_color_id">
                                        <option value="">Rəng seçin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="image" class="form-label">Şəkil <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" required>
                                    @error('image')
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
                                        <label for="alt_text_az" class="form-label">Alt Mətn (AZ)</label>
                                        <input type="text" class="form-control" id="alt_text_az" name="alt_text_az" value="{{ old('alt_text_az') }}">
                                    </div>
                                </div>

                                <!-- En tab -->
                                <div class="tab-pane" id="en" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="alt_text_en" class="form-label">Alt Mətn (EN)</label>
                                        <input type="text" class="form-control" id="alt_text_en" name="alt_text_en" value="{{ old('alt_text_en') }}">
                                    </div>
                                </div>

                                <!-- Ru tab -->
                                <div class="tab-pane" id="ru" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="alt_text_ru" class="form-label">Alt Mətn (RU)</label>
                                        <input type="text" class="form-control" id="alt_text_ru" name="alt_text_ru" value="{{ old('alt_text_ru') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_main" name="is_main" {{ old('is_main') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_main">Əsas Şəkil</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{ old('status', 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Aktiv</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="sort_order" class="form-label">Sıralama</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">Yadda saxla</button>
                                    <a href="{{ route('back.pages.product_images.index') }}" class="btn btn-secondary">Ləğv et</a>
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
        // Ürün seçildiğinde renkleri getir
        $('#product_id').change(function() {
            var productId = $(this).val();
            if (productId) {
                $.ajax({
                    url: "{{ url('admin/pages/product_images/get-colors-by-product') }}/" + productId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#product_color_id').empty();
                        $('#product_color_id').append('<option value="">Rəng seçin</option>');
                        $.each(data, function(key, value) {
                            $('#product_color_id').append('<option value="' + value.id + '">' + value.color_name_az + '</option>');
                        });
                    }
                });
            } else {
                $('#product_color_id').empty();
                $('#product_color_id').append('<option value="">Rəng seçin</option>');
            }
        });
    });
</script>
@endpush 