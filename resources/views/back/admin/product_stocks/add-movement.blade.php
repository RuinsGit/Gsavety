@extends('back.layouts.master')

@section('title', 'Stok Hərəkəti Əlavə Et')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Stok Hərəkəti Əlavə Et</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.product_stocks.index') }}">Məhsul Stokları</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.product_stocks.show', $stock->id) }}">Stok Göstər</a></li>
                            <li class="breadcrumb-item active">Hərəkət Əlavə Et</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">Məhsul Məlumatları</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered mb-0">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 40%">Məhsul</th>
                                                    <td>{{ $stock->product->name_az }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Rəng</th>
                                                    <td>{{ $stock->color ? $stock->color->color_name_az : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ölçü</th>
                                                    <td>{{ $stock->size ? $stock->size->size_name_az : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>SKU</th>
                                                    <td>{{ $stock->sku ?: '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">Stok Məlumatları</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered mb-0">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 40%">Cari Stok Miqdarı</th>
                                                    <td>
                                                        <span class="badge {{ $stock->quantity > $stock->low_stock_threshold ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $stock->quantity }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Aşağı Stok Həddi</th>
                                                    <td>{{ $stock->low_stock_threshold }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        <span class="badge {{ $stock->status ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $stock->status ? 'Aktiv' : 'Deaktiv' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

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

                        <form action="{{ route('back.pages.product_stocks.store-movement', $stock->id) }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Hərəkət Növü <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Hərəkət növü seçin</option>
                                        <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stok Girişi</option>
                                        <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stok Çıxışı</option>
                                    </select>
                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Miqdar <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                                    @error('quantity')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="reason" class="form-label">Səbəb</label>
                                    <select class="form-select" id="reason" name="reason">
                                        <option value="">Səbəb seçin</option>
                                        <option value="purchase" {{ old('reason') == 'purchase' ? 'selected' : '' }}>Satın alma</option>
                                        <option value="sale" {{ old('reason') == 'sale' ? 'selected' : '' }}>Satış</option>
                                        <option value="return" {{ old('reason') == 'return' ? 'selected' : '' }}>İadə</option>
                                        <option value="adjustment" {{ old('reason') == 'adjustment' ? 'selected' : '' }}>Stok düzəlişi</option>
                                        <option value="damage" {{ old('reason') == 'damage' ? 'selected' : '' }}>Zədələnmə</option>
                                        <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Digər</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="reference" class="form-label">Referans</label>
                                    <input type="text" class="form-control" id="reference" name="reference" value="{{ old('reference') }}" placeholder="Sifariş nömrəsi, faktura və s.">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="note" class="form-label">Qeyd</label>
                                    <textarea class="form-control" id="note" name="note" rows="3">{{ old('note') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">Yadda saxla</button>
                                    <a href="{{ route('back.pages.product_stocks.show', $stock->id) }}" class="btn btn-secondary">Ləğv et</a>
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
        // Hərəkət növü dəyişdikdə səbəbləri güncəllə
        $('#type').change(function() {
            var type = $(this).val();
            var reason = $('#reason');
            
            reason.empty();
            reason.append('<option value="">Səbəb seçin</option>');
            
            if (type === 'in') {
                reason.append('<option value="purchase">Satın alma</option>');
                reason.append('<option value="return">İadə</option>');
                reason.append('<option value="adjustment">Stok düzəlişi</option>');
                reason.append('<option value="other">Digər</option>');
            } else if (type === 'out') {
                reason.append('<option value="sale">Satış</option>');
                reason.append('<option value="return">İadə</option>');
                reason.append('<option value="damage">Zədələnmə</option>');
                reason.append('<option value="adjustment">Stok düzəlişi</option>');
                reason.append('<option value="other">Digər</option>');
            }
        });
    });
</script>
@endpush 