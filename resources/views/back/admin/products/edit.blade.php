@extends('back.layouts.master')

@section('title', 'Məhsul Redaktə Et')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Məhsul Redaktə Et</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.products.index') }}">Məhsullar</a></li>
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

                        <form action="{{ route('back.pages.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Ana Sekmeler -->
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-4" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#product_info" role="tab" style="color:rgb(0, 0, 0);">
                                        <i class="ri-shopping-bag-3-line me-1 align-middle"></i> Məhsul Məlumatları
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#language_info" role="tab" style="color:rgb(0, 0, 0);">
                                        <i class="ri-translate-2 me-1 align-middle"></i> Dil Məlumatları
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#product_properties" role="tab" style="color:rgb(0, 0, 0);">
                                        <i class="ri-list-check-2 me-1 align-middle"></i> Məhsul Xüsusiyyətləri
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#product_categories" role="tab" style="color:rgb(0, 0, 0);">
                                        <i class="ri-folder-line me-1 align-middle"></i> Kateqoriyalar
                                    </a>
                                </li>
                            </ul>

                            <!-- Ana Sekme İçerikleri -->
                            <div class="tab-content p-3">
                                <!-- Məhsul Məlumatları Sekmesi -->
                                <div class="tab-pane active" id="product_info" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Əsas Məlumatlar</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="reference" class="form-label">Referans</label>
                                                        <input type="text" class="form-control" id="reference" name="reference" value="{{ old('reference', $product->reference) }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                                        @error('sku')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Qiymət Məlumatları</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="price" class="form-label">Qiymət <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ri-money-dollar-circle-line"></i></span>
                                                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                                        </div>
                                                        @error('price')
                                                            <div class="text-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="discount_price" class="form-label">Endirimli Qiymət</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ri-price-tag-3-line"></i></span>
                                                            <input type="number" step="0.01" class="form-control" id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Şəkil</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="main_image" class="form-label">Əsas Şəkil</label>
                                                        @if($product->main_image)
                                                            <div class="mb-2">
                                                                <img src="{{ asset($product->main_image) }}" alt="{{ $product->name_az }}" width="100" class="img-thumbnail">
                                                            </div>
                                                        @endif
                                                        <input type="file" class="form-control @error('main_image') is-invalid @enderror" id="main_image" name="main_image">
                                                        <div class="mt-2 text-muted small">Tövsiyə olunan ölçü: 800x800px</div>
                                                        @error('main_image')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div id="image-preview" class="mt-3 d-none">
                                                        <img src="" alt="Önizləmə" class="img-thumbnail" style="max-height: 200px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Status</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <div class="form-check form-switch form-switch-success mb-3">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_featured">Önə Çıxan</label>
                                                        </div>
                                                        <div class="form-check form-switch form-switch-success">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{ old('status', $product->status) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="status">Aktiv</label>
                                                        </div>
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
                                                        <label for="name_az" class="form-label">Məhsul Adı (AZ) <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name_az') is-invalid @enderror" id="name_az" name="name_az" value="{{ old('name_az', $product->name_az) }}" required>
                                                        @error('name_az')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description_az" class="form-label">Təsvir (AZ)</label>
                                                        <textarea class="form-control" id="description_az" name="description_az" rows="4">{{ old('description_az', $product->description_az) }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="meta_title_az" class="form-label">Meta Başlıq (AZ)</label>
                                                        <input type="text" class="form-control" id="meta_title_az" name="meta_title_az" value="{{ old('meta_title_az', $product->meta_title_az) }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="meta_description_az" class="form-label">Meta Təsvir (AZ)</label>
                                                        <textarea class="form-control" id="meta_description_az" name="meta_description_az" rows="2">{{ old('meta_description_az', $product->meta_description_az) }}</textarea>
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
                                                        <label for="name_en" class="form-label">Məhsul Adı (EN) <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $product->name_en) }}" required>
                                                        @error('name_en')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description_en" class="form-label">Təsvir (EN)</label>
                                                        <textarea class="form-control" id="description_en" name="description_en" rows="4">{{ old('description_en', $product->description_en) }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="meta_title_en" class="form-label">Meta Başlıq (EN)</label>
                                                        <input type="text" class="form-control" id="meta_title_en" name="meta_title_en" value="{{ old('meta_title_en', $product->meta_title_en) }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="meta_description_en" class="form-label">Meta Təsvir (EN)</label>
                                                        <textarea class="form-control" id="meta_description_en" name="meta_description_en" rows="2">{{ old('meta_description_en', $product->meta_description_en) }}</textarea>
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
                                                        <label for="name_ru" class="form-label">Məhsul Adı (RU) <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name_ru') is-invalid @enderror" id="name_ru" name="name_ru" value="{{ old('name_ru', $product->name_ru) }}" required>
                                                        @error('name_ru')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description_ru" class="form-label">Təsvir (RU)</label>
                                                        <textarea class="form-control" id="description_ru" name="description_ru" rows="4">{{ old('description_ru', $product->description_ru) }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="meta_title_ru" class="form-label">Meta Başlıq (RU)</label>
                                                        <input type="text" class="form-control" id="meta_title_ru" name="meta_title_ru" value="{{ old('meta_title_ru', $product->meta_title_ru) }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="meta_description_ru" class="form-label">Meta Təsvir (RU)</label>
                                                        <textarea class="form-control" id="meta_description_ru" name="meta_description_ru" rows="2">{{ old('meta_description_ru', $product->meta_description_ru) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Məhsul Xüsusiyyətləri Sekmesi -->
                                <div class="tab-pane" id="product_properties" role="tabpanel">
                                    <div class="card border shadow-none mb-4">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">Məhsul Xüsusiyyətləri</h5>
                                            <button type="button" id="add-property" class="btn btn-sm btn-primary">
                                                <i class="ri-add-line align-bottom"></i> Xüsusiyyət Əlavə Et
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div id="properties-container">
                                                @if($product->properties->count() > 0)
                                                    @foreach($product->properties as $index => $property)
                                                        <div class="property-item mb-4 p-3 border rounded bg-light">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-3">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <h6 class="mb-0">Xüsusiyyət #{{ $index + 1 }}</h6>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-property">
                                                                            <i class="ri-delete-bin-line"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row mb-3">
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Xüsusiyyət Adı (AZ)</label>
                                                                    <input type="text" class="form-control" name="property_name_az[]" value="{{ $property->property_name_az }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Xüsusiyyət Adı (EN)</label>
                                                                    <input type="text" class="form-control" name="property_name_en[]" value="{{ $property->property_name_en }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Xüsusiyyət Adı (RU)</label>
                                                                    <input type="text" class="form-control" name="property_name_ru[]" value="{{ $property->property_name_ru }}">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Xüsusiyyət Dəyəri (AZ)</label>
                                                                    <input type="text" class="form-control" name="property_value_az[]" placeholder="AZ" value="{{ $property->property_value_az }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Xüsusiyyət Dəyəri (EN)</label>
                                                                    <input type="text" class="form-control" name="property_value_en[]" placeholder="EN" value="{{ $property->property_value_en }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Xüsusiyyət Dəyəri (RU)</label>
                                                                    <input type="text" class="form-control" name="property_value_ru[]" placeholder="RU" value="{{ $property->property_value_ru }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="property-item mb-4 p-3 border rounded bg-light">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <h6 class="mb-0">Xüsusiyyət #1</h6>
                                                                    <button type="button" class="btn btn-sm btn-danger remove-property">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Xüsusiyyət Adı (AZ)</label>
                                                                <input type="text" class="form-control" name="property_name_az[]">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Xüsusiyyət Adı (EN)</label>
                                                                <input type="text" class="form-control" name="property_name_en[]">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Xüsusiyyət Adı (RU)</label>
                                                                <input type="text" class="form-control" name="property_name_ru[]">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Xüsusiyyət Dəyəri (AZ)</label>
                                                                <input type="text" class="form-control" name="property_value_az[]" placeholder="AZ">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Xüsusiyyət Dəyəri (EN)</label>
                                                                <input type="text" class="form-control" name="property_value_en[]" placeholder="EN">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Xüsusiyyət Dəyəri (RU)</label>
                                                                <input type="text" class="form-control" name="property_value_ru[]" placeholder="RU">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kateqoriyalar Sekmesi -->
                                <div class="tab-pane" id="product_categories" role="tabpanel">
                                    <div class="card border shadow-none mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Kateqoriyalar</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="categories" class="form-label">Məhsul Kateqoriyaları <span class="text-danger">*</span></label>
                                                <select class="form-select select2" id="categories" name="categories[]" multiple>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                                                            {{ $category->name_az }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-text text-muted">
                                                    Ən azı bir kateqoriya seçilməlidir. Birdən çox seçmək üçün CTRL düyməsini basılı saxlayın.
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
                                    <a href="{{ route('back.pages.products.index') }}" class="btn btn-secondary">
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
    
    .property-item {
        transition: all 0.3s;
    }
    
    .property-item:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3498db;
        border-color: #2980b9;
    }
</style>
@endsection

@push('js')
<script>
    $(document).ready(function() {
      
        $('#add-property').click(function() {
            var propertyCount = $('.property-item').length + 1;
            var propertyItem = $('.property-item').first().clone();
            propertyItem.find('input').val('');
            propertyItem.find('select').val('fit');
            propertyItem.find('h6').text('Xüsusiyyət #' + propertyCount);
            $('#properties-container').append(propertyItem);
        });

  
        $(document).on('click', '.remove-property', function() {
            if ($('.property-item').length > 1) {
                $(this).closest('.property-item').remove();
              
                $('.property-item').each(function(index) {
                    $(this).find('h6').text('Xüsusiyyət #' + (index + 1));
                });
            } else {
                alert('Ən azı bir xüsusiyyət olmalıdır.');
            }
        });
        

        $('#main_image').change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').removeClass('d-none');
                    $('#image-preview img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

  
        $('.select2').select2({
            placeholder: 'Kateqoriyaları seçin',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush 