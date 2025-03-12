@extends('back.layouts.master')

@section('title', 'Məhsul Stoku Göstər')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Məhsul Stoku Göstər</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.product_stocks.index') }}">Məhsul Stokları</a></li>
                            <li class="breadcrumb-item active">Göstər</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('back.pages.product_stocks.edit', $stock->id) }}" class="btn btn-primary me-2">
                                <i class="fas fa-edit"></i> Redaktə et
                            </a>
                            <form id="delete-form" action="{{ route('back.pages.product_stocks.destroy', $stock->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                    <i class="fas fa-trash"></i> Sil
                                </button>
                            </form>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">Məhsul Məlumatları</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped mb-0">
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
                                                    <tr>
                                                        <th>Barkod</th>
                                                        <td>{{ $stock->barcode ?: '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">Stok Məlumatları</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 40%">Stok Miqdarı</th>
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
                                                    <tr>
                                                        <th>Yaradılma tarixi</th>
                                                        <td>{{ $stock->created_at->format('d.m.Y H:i') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Yenilənmə tarixi</th>
                                                        <td>{{ $stock->updated_at->format('d.m.Y H:i') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($stock->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">Qeydlər</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $stock->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">Stok Hərəkətləri</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info mb-0">
                                            Bu xüsusiyyət hal hazırda aktiv deyil.
                                        </div>
                                        <!-- stock movements content is temporarily disabled 
                                        @if(isset($stock->stockMovements) && count($stock->stockMovements) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Tarix</th>
                                                            <th>Hərəkət Növü</th>
                                                            <th>Miqdar</th>
                                                            <th>Qeyd</th>
                                                            <th>İstifadəçi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($stock->stockMovements as $key => $movement)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $movement->created_at->format('d.m.Y H:i') }}</td>
                                                            <td>
                                                                <span class="badge {{ $movement->type == 'in' ? 'bg-success' : 'bg-danger' }}">
                                                                    {{ $movement->type == 'in' ? 'Giriş' : 'Çıxış' }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $movement->quantity }}</td>
                                                            <td>{{ $movement->note ?: '-' }}</td>
                                                            <td>{{ $movement->user ? $movement->user->name : '-' }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="alert alert-info mb-0">
                                                Bu stok üçün hərəkət tapılmadı.
                                            </div>
                                        @endif
                                        -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <a href="{{ route('back.pages.product_stocks.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Geri qayıt
                                </a>
                                <!-- <a href="{{ route('back.pages.product_stocks.add-movement', $stock->id) }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Stok Hərəkəti Əlavə Et
                                </a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function confirmDelete() {
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
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
@endpush 