@extends('back.layouts.master')

@section('title', 'Məhsul Detalları')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Məhsul Detalları</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.products.index') }}">Məhsullar</a></li>
                            <li class="breadcrumb-item active">Detallar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('back.pages.products.edit', $product->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Redaktə et
                    </a>
                    <a href="{{ route('back.pages.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Geri
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <div class="card product-info-card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-information-line me-2"></i> Əsas Məlumatlar
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if($product->main_image)
                            <div class="position-relative product-img-wrapper mb-4">
                                <img src="{{ asset($product->main_image) }}" alt="{{ $product->name_az }}" class="img-fluid rounded-3 product-main-img">
                                <div class="product-badges">
                                    @if($product->is_featured)
                                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
                                            <i class="ri-star-fill me-1"></i> Önə Çıxan
                                        </span>
                                    @endif
                                    @if(!$product->status)
                                        <span class="badge bg-danger position-absolute bottom-0 start-0 m-2">
                                            <i class="ri-close-circle-line me-1"></i> Deaktiv
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="no-image-placeholder bg-light rounded-3 mb-4 d-flex align-items-center justify-content-center" style="height: 300px;">
                                <div class="text-center text-muted">
                                    <i class="ri-image-line" style="font-size: 48px;"></i>
                                    <p class="mt-2">Şəkil yoxdur</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="product-details">
                            <div class="info-row d-flex justify-content-between border-bottom py-2">
                                <strong class="text-dark"><i class="ri-barcode-line me-2 text-primary"></i>SKU:</strong>
                                <span>{{ $product->sku }}</span>
                            </div>
                            
                            <div class="info-row d-flex justify-content-between border-bottom py-2">
                                <strong class="text-dark"><i class="ri-hashtag me-2 text-primary"></i>Referans:</strong>
                                <span>{{ $product->reference ?: 'Təyin olunmayıb' }}</span>
                            </div>
                            
                            <div class="info-row d-flex justify-content-between border-bottom py-2">
                                <strong class="text-dark"><i class="ri-money-dollar-circle-line me-2 text-primary"></i>Qiymət:</strong>
                                <span class="fw-bold">{{ $product->price }} ₼</span>
                            </div>
                            
                            <div class="info-row d-flex justify-content-between border-bottom py-2">
                                <strong class="text-dark"><i class="ri-price-tag-3-line me-2 text-primary"></i>Endirimli Qiymət:</strong>
                                @if($product->discount_price)
                                    <span class="fw-bold text-success">{{ $product->discount_price }} ₼</span>
                                @else
                                    <span>Təyin olunmayıb</span>
                                @endif
                            </div>
                            
                            <div class="info-row d-flex justify-content-between border-bottom py-2">
                                <strong class="text-dark"><i class="ri-star-line me-2 text-primary"></i>Önə Çıxan:</strong>
                                @if($product->is_featured)
                                    <span class="badge bg-success rounded-pill">Bəli</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">Xeyr</span>
                                @endif
                            </div>
                            
                            <div class="info-row d-flex justify-content-between py-2">
                                <strong class="text-dark"><i class="ri-toggle-line me-2 text-primary"></i>Status:</strong>
                                @if($product->status)
                                    <span class="badge bg-success rounded-pill">Aktiv</span>
                                @else
                                    <span class="badge bg-danger rounded-pill">Deaktiv</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-8">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-information-line me-2"></i> Məzmun Məlumatları
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <ul class="nav nav-pills nav-primary mb-3" role="tablist">
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link active" data-bs-toggle="tab" href="#content-az" role="tab">
                                    <i class="ri-global-line me-1"></i> Azərbaycan
                                </a>
                            </li>
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link" data-bs-toggle="tab" href="#content-en" role="tab">
                                    <i class="ri-global-line me-1"></i> İngilis
                                </a>
                            </li>
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link" data-bs-toggle="tab" href="#content-ru" role="tab">
                                    <i class="ri-global-line me-1"></i> Rus
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3">
                            <div class="tab-pane active" id="content-az" role="tabpanel">
                                <div class="content-box p-3 border rounded-3 bg-light mb-4">
                                    <h5 class="content-title d-flex align-items-center mb-3">
                                        <i class="ri-text me-2 text-primary"></i> Məhsul Adı (AZ)
                                    </h5>
                                    <p class="mb-0 fs-5 fw-medium">{{ $product->name_az }}</p>
                                </div>
                                
                                <div class="content-box p-3 border rounded-3 bg-light mb-4">
                                    <h5 class="content-title d-flex align-items-center mb-3">
                                        <i class="ri-file-text-line me-2 text-primary"></i> Təsvir (AZ)
                                    </h5>
                                    <div class="p-2 bg-white rounded">
                                        {!! $product->description_az ?? '<span class="text-muted">Təsvir yoxdur</span>' !!}
                                    </div>
                                </div>
                                
                                <div class="content-box p-3 border rounded-3 bg-light mb-4">
                                    <h5 class="content-title d-flex align-items-center mb-3">
                                        <i class="ri-search-line me-2 text-primary"></i> SEO Məlumatları (AZ)
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <tr>
                                                <th class="bg-light">Meta Başlıq</th>
                                                <td>{{ $product->meta_title_az ?? 'Təyin olunmayıb' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Meta Təsvir</th>
                                                <td>{{ $product->meta_description_az ?? 'Təyin olunmayıb' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Slug</th>
                                                <td>{{ $product->slug_az ?? 'Təyin olunmayıb' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="content-en" role="tabpanel">
                                <div class="content-box p-3 border rounded-3 bg-light mb-4">
                                    <h5 class="content-title d-flex align-items-center mb-3">
                                        <i class="ri-text me-2 text-primary"></i> Məhsul Adı (EN)
                                    </h5>
                                    <p class="mb-0 fs-5 fw-medium">{{ $product->name_en }}</p>
                                </div>
                                
                                <div class="content-box p-3 border rounded-3 bg-light mb-4">
                                    <h5 class="content-title d-flex align-items-center mb-3">
                                        <i class="ri-file-text-line me-2 text-primary"></i> Təsvir (EN)
                                    </h5>
                                    <div class="p-2 bg-white rounded">
                                        {!! $product->description_en ?? '<span class="text-muted">Təsvir yoxdur</span>' !!}
                                    </div>
                                </div>
                                
                                <div class="content-box p-3 border rounded-3 bg-light mb-4">
                                    <h5 class="content-title d-flex align-items-center mb-3">
                                        <i class="ri-search-line me-2 text-primary"></i> SEO Məlumatları (EN)
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <tr>
                                                <th class="bg-light">Meta Başlıq</th>
                                                <td>{{ $product->meta_title_en ?? 'Təyin olunmayıb' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Meta Təsvir</th>
                                                <td>{{ $product->meta_description_en ?? 'Təyin olunmayıb' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Slug</th>
                                                <td>{{ $product->slug_en ?? 'Təyin olunmayıb' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="content-ru" role="tabpanel">
                                <div class="content-box p-3 border rounded-3 bg-light mb-4">
                                    <h5 class="content-title d-flex align-items-center mb-3">
                                        <i class="ri-text me-2 text-primary"></i> Məhsul Adı (RU)
                                    </h5>
                                    <p class="mb-0 fs-5 fw-medium">{{ $product->name_ru }}</p>
                                </div>
                                
                                <div class="content-box p-3 border rounded-3 bg-light mb-4">
                                    <h5 class="content-title d-flex align-items-center mb-3">
                                        <i class="ri-file-text-line me-2 text-primary"></i> Təsvir (RU)
                                    </h5>
                                    <div class="p-2 bg-white rounded">
                                        {!! $product->description_ru ?? '<span class="text-muted">Təsvir yoxdur</span>' !!}
                                    </div>
                                </div>
                                
                                <div class="content-box p-3 border rounded-3 bg-light mb-4">
                                    <h5 class="content-title d-flex align-items-center mb-3">
                                        <i class="ri-search-line me-2 text-primary"></i> SEO Məlumatları (RU)
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <tr>
                                                <th class="bg-light">Meta Başlıq</th>
                                                <td>{{ $product->meta_title_ru ?? 'Təyin olunmayıb' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Meta Təsvir</th>
                                                <td>{{ $product->meta_description_ru ?? 'Təyin olunmayıb' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Slug</th>
                                                <td>{{ $product->slug_ru ?? 'Təyin olunmayıb' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            @if($product->properties->count() > 0)
            <div class="col-xl-6 mb-4">
                <div class="card shadow-lg border-0 h-100 rounded-4 overflow-hidden">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-list-check-2 me-2"></i> Məhsul Xüsusiyyətləri
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <ul class="nav nav-pills nav-success mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#prop_az" role="tab">
                                    <i class="ri-global-line me-1"></i> Azərbaycan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#prop_en" role="tab">
                                    <i class="ri-global-line me-1"></i> İngilis
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#prop_ru" role="tab">
                                    <i class="ri-global-line me-1"></i> Rus
                                </a>
                            </li>
                        </ul>
                        
                        <div class="tab-content">
                            <div class="tab-pane active" id="prop_az" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">#</th>
                                                <th>Xüsusiyyət Adı (AZ)</th>
                                                <th>Xüsusiyyət Dəyəri (AZ)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->properties as $index => $property)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $property->property_name_az }}</td>
                                                <td>{{ $property->property_value_az }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="prop_en" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">#</th>
                                                <th>Xüsusiyyət Adı (EN)</th>
                                                <th>Xüsusiyyət Dəyəri (EN)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->properties as $index => $property)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $property->property_name_en }}</td>
                                                <td>{{ $property->property_value_en }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="prop_ru" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">#</th>
                                                <th>Xüsusiyyət Adı (RU)</th>
                                                <th>Xüsusiyyət Dəyəri (RU)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->properties as $index => $property)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $property->property_name_ru }}</td>
                                                <td>{{ $property->property_value_ru }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if($product->colors->count() > 0)
            <div class="col-xl-6 mb-4">
                <div class="card shadow-lg border-0 h-100 rounded-4 overflow-hidden">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-palette-line me-2"></i> Məhsul Rəngləri
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Rəng Kodu</th>
                                        <th>Rəng Adı</th>
                                        <th class="text-center">Rəng Nümunəsi</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->colors as $index => $color)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $color->color_code }}</td>
                                        <td>{{ $color->color_name }}</td>
                                        <td class="text-center">
                                            <div class="color-sample" style="width: 32px; height: 32px; border-radius: 50%; background-color: {{ $color->color_code }}; display: inline-block; border: 1px solid #ddd;"></div>
                                        </td>
                                        <td class="text-center">
                                            @if($color->status)
                                                <span class="badge bg-success rounded-pill">Aktiv</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill">Deaktiv</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="row">
            @if($product->sizes->count() > 0)
            <div class="col-xl-6 mb-4">
                <div class="card shadow-lg border-0 h-100 rounded-4 overflow-hidden">
                    <div class="card-header bg-warning text-dark py-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-drag-move-2-line me-2"></i> Məhsul Ölçüləri
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Ölçü Adı</th>
                                        <th>Ölçü Kodu</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->sizes as $index => $size)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $size->size_name }}</td>
                                        <td>{{ $size->size_code }}</td>
                                        <td class="text-center">
                                            @if($size->status)
                                                <span class="badge bg-success rounded-pill">Aktiv</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill">Deaktiv</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if($product->stocks->count() > 0)
            <div class="col-xl-6 mb-4">
                <div class="card shadow-lg border-0 h-100 rounded-4 overflow-hidden">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-stock-line me-2"></i> Məhsul Stok Məlumatları
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Rəng</th>
                                        <th>Ölçü</th>
                                        <th class="text-center">Miqdar</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->stocks as $index => $stock)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            @if($stock->color)
                                                <div class="d-flex align-items-center">
                                                    <div class="color-dot" style="width: 16px; height: 16px; border-radius: 50%; background-color: {{ $stock->color->color_code }}; margin-right: 6px; border: 1px solid #ddd;"></div>
                                                    {{ $stock->color->color_name }}
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($stock->size)
                                                {{ $stock->size->size_name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-medium {{ $stock->quantity <= 5 ? 'text-danger' : 'text-success' }}">
                                                {{ $stock->quantity }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($stock->status)
                                                <span class="badge bg-success rounded-pill">Aktiv</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill">Deaktiv</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        @if($product->images->count() > 0)
        <div class="row mt-0 mb-4">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-purple text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-image-2-line me-2"></i> Məhsul Şəkilləri
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach($product->images as $image)
                            <div class="col-md-3 col-sm-4 col-6">
                                <div class="card product-image-card border h-100">
                                    <div class="position-relative">
                                        <img src="{{ asset($image->image_path) }}" class="card-img-top product-gallery-img" alt="Məhsul şəkli">
                                        @if($image->is_main)
                                            <span class="badge bg-success position-absolute top-0 end-0 m-2">Əsas şəkil</span>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary">Şəkil #{{ $loop->iteration }}</span>
                                            @if($image->status)
                                                <span class="badge bg-success">Aktiv</span>
                                            @else
                                                <span class="badge bg-danger">Deaktiv</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>

.card {
    transition: all 0.3s ease;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06) !important;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 28px rgba(0, 0, 0, 0.1) !important;
}

.card-header {
    border-bottom: none;
}

.page-title-box h4 {
    font-weight: 600;
    color: #3b3f5c;
}

.breadcrumb-item+.breadcrumb-item::before {
    font-family: "remixicon";
    content: "\EA6C";
    font-size: 1rem;
    vertical-align: middle;
}


.product-img-wrapper {
    height: 300px;
    overflow: hidden;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-main-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.5s;
}

.product-img-wrapper:hover .product-main-img {
    transform: scale(1.05);
}


.product-info-card .info-row {
    transition: background-color 0.2s;
}

.product-info-card .info-row:hover {
    background-color: rgba(52, 152, 219, 0.05);
}


.nav-pills .nav-link {
    padding: 10px 20px;
    font-weight: 500;
    border-radius: 5px;
    transition: all 0.2s;
}

.nav-pills .nav-link.active {
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
}

.nav-pills.nav-primary .nav-link.active {
    background-color: #3498db;
}

.nav-pills.nav-success .nav-link.active {
    background-color: #28a745;
}

.nav-pills.nav-warning .nav-link.active {
    background-color: #ffc107;
    color: #333;
}

.nav-pills.nav-danger .nav-link.active {
    background-color: #dc3545;
}


.table {
    font-size: 0.95rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(52, 152, 219, 0.05);
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}


.product-gallery-img {
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s;
}

.product-image-card:hover .product-gallery-img {
    transform: scale(1.05);
}


.bg-purple {
    background-color: #6f42c1;
}


@media (max-width: 767.98px) {
    .product-img-wrapper {
        height: 250px;
    }
    
    .product-gallery-img {
        height: 150px;
    }
}

.rounded-4 {
    border-radius: 10px !important;
}
</style>
@endsection 