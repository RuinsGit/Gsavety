@extends('back.layouts.master')

@section('title', 'Yeni Məhsul Stoku Əlavə Et')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Yeni Məhsul Stoku Əlavə Et</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.product_stocks.index') }}">Məhsul Stokları</a></li>
                            <li class="breadcrumb-item active">Yeni Əlavə</li>
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

                        <form action="{{ route('back.pages.product_stocks.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="product_id" class="form-label">Məhsul <span class="text-danger">*</span></label>
                                    <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                        <option value="">Məhsul seçin</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name_az }}</option>
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
                                <div class="col-md-6">
                                    <label for="product_size_id" class="form-label">Ölçü</label>
                                    <select class="form-select" id="product_size_id" name="product_size_id">
                                        <option value="">Ölçü seçin</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Stok Miqdarı <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 0) }}" min="0" required>
                                    @error('quantity')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="sku" class="form-label">SKU (Stok Kodu)</label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}">
                                    @error('sku')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- <div class="col-md-6">
                                    <label for="barcode" class="form-label">Barkod</label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode" name="barcode" value="{{ old('barcode') }}">
                                    @error('barcode')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div> -->
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch form-switch-success mt-4">
                                        <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{ old('status', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Aktiv</label>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                                    <label for="low_stock_threshold" class="form-label">Aşağı Stok Həddi</label>
                                    <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" min="0">
                                    <small class="text-muted">Stok bu həddən aşağı düşdükdə xəbərdarlıq ediləcək</small>
                                </div> -->
                            </div>

                            <!-- <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="notes" class="form-label">Qeydlər</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                </div>
                            </div> -->

                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">Yadda saxla</button>
                                    <a href="{{ route('back.pages.product_stocks.index') }}" class="btn btn-secondary">Ləğv et</a>
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
        // Ürün seçildiğinde renkleri ve ölçüleri getir
        $('#product_id').change(function() {
            var productId = $(this).val();
            if (productId) {
                // Renkleri getir
                $.ajax({
                    url: "{{ url('admin/pages/product_stocks/get-colors-by-product') }}/" + productId,
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

                // Ölçüleri getir
                $.ajax({
                    url: "{{ url('admin/pages/product_stocks/get-sizes-by-product') }}/" + productId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#product_size_id').empty();
                        $('#product_size_id').append('<option value="">Ölçü seçin</option>');
                        $.each(data, function(key, value) {
                            $('#product_size_id').append('<option value="' + value.id + '">' + value.size_name_az + '</option>');
                        });
                    }
                });

                // SKU oluştur
                generateSku();
            } else {
                $('#product_color_id').empty();
                $('#product_color_id').append('<option value="">Rəng seçin</option>');
                $('#product_size_id').empty();
                $('#product_size_id').append('<option value="">Ölçü seçin</option>');
                $('#sku').val('');
            }
        });

        // Renk veya ölçü değiştiğinde SKU oluştur
        $('#product_color_id, #product_size_id').change(function() {
            generateSku();
        });

        function generateSku() {
            var productId = $('#product_id').val();
            var colorId = $('#product_color_id').val();
            var sizeId = $('#product_size_id').val();

            if (productId) {
                var sku = 'P' + productId;
                
                if (colorId) {
                    sku += 'C' + colorId;
                }
                
                if (sizeId) {
                    sku += 'S' + sizeId;
                }
                
                $('#sku').val(sku);
            }
        }
    });
</script>
@endpush 