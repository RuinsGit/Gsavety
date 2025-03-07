@extends('back.layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Məhsullar</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Məhsullar</a></li>
                                <li class="breadcrumb-item active">Redaktə et</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Məhsul redaktə et</h4>
                            <ul class="nav nav-pills nav-justified" role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#az" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block">AZ</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-bs-toggle="tab" href="#en" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block">EN</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-bs-toggle="tab" href="#ru" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                        <span class="d-none d-sm-block">RU</span>
                                    </a>
                                </li>
                            </ul>
                            <form class="needs-validation" method="POST"
                                action="{{ route('admin.product.update', ['id' => $product->id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="az">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Başlıq (Az)</label>
                                                    <input type="text" name="title_az" value="{{ $product->title_az }}"
                                                        class="form-control">
                                                    @error('title_az')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Şəkil başlıq
                                                        (Az)</label>
                                                    <input type="text" name="image_title_az"
                                                        value="{{ $product->image_title_az }}" class="form-control">
                                                    @error('image_title_az')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Şəkil alt
                                                        (Az)</label>
                                                    <input type="text" name="image_alt_az"
                                                        value="{{ $product->image_alt_az }}" class="form-control">
                                                    @error('image_alt_az')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Meta title
                                                        (Az)</label>
                                                    <input type="text" name="meta_title_az"
                                                        value="{{ $product->meta_title_az }}" class="form-control">
                                                    @error('meta_title_az')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Meta description
                                                        (Az)</label>
                                                    <input type="text" name="meta_description_az"
                                                        value="{{ $product->meta_description_az }}" class="form-control">
                                                    @error('meta_description_az')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Mətn (Az)</label>
                                                    <textarea name="description_az" class="summernote form-control">{{ $product->description_az }}</textarea>
                                                    @error('description_az')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="en">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Başlıq (Az)</label>
                                                    <input type="text" name="title_en"
                                                        value="{{ $product->title_en }}" class="form-control">
                                                    @error('title_en')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Şəkil başlıq
                                                        (En)</label>
                                                    <input type="text" name="image_title_en"
                                                        value="{{ $product->image_title_en }}" class="form-control">
                                                    @error('image_title_en')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Şəkil alt
                                                        (En)</label>
                                                    <input type="text" name="image_alt_en"
                                                        value="{{ $product->image_alt_en }}" class="form-control">
                                                    @error('image_alt_en')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Meta title
                                                        (En)</label>
                                                    <input type="text" name="meta_title_en"
                                                        value="{{ $product->meta_title_en }}" class="form-control">
                                                    @error('meta_title_en')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Meta description
                                                        (En)</label>
                                                    <input type="text" name="meta_description_en"
                                                        value="{{ $product->meta_description_en }}" class="form-control">
                                                    @error('meta_description_en')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Mətn (En)</label>
                                                    <textarea name="description_en" class="summernote form-control">{{ $product->description_en }}</textarea>
                                                    @error('description_en')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="ru">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Başlıq (Ru)</label>
                                                    <input type="text" name="title_ru"
                                                        value="{{ $product->title_ru }}" class="form-control">
                                                    @error('title_ru')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Şəkil başlıq
                                                        (Ru)</label>
                                                    <input type="text" name="image_title_ru"
                                                        value="{{ $product->image_title_ru }}" class="form-control">
                                                    @error('image_title_ru')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Şəkil alt
                                                        (Ru)</label>
                                                    <input type="text" name="image_alt_ru"
                                                        value="{{ $product->image_alt_ru }}" class="form-control">
                                                    @error('image_alt_ru')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Meta title
                                                        (Ru)</label>
                                                    <input type="text" name="meta_title_ru"
                                                        value="{{ $product->meta_title_ru }}" class="form-control">
                                                    @error('meta_title_ru')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Meta description
                                                        (Ru)</label>
                                                    <input type="text" name="meta_description_ru"
                                                        value="{{ $product->meta_description_ru }}" class="form-control">
                                                    @error('meta_description_ru')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Mətn (Ru)</label>
                                                    <textarea name="description_ru" class="summernote form-control">{{ $product->description_ru }}</textarea>
                                                    @error('description_ru')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Satış qiyməti</label>
                                            <input type="text" class="form-control" name="sale_price"
                                                value="{{ $product->sale_price }}">
                                            @error('sale_price')
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Endirimli qiymət</label>
                                            <input type="text" class="form-control" name="discount"
                                                value="{{ $product->discount }}">
                                            @error('discount')
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Say</label>
                                            <input type="number" name="count" value="{{ $product->count }}"
                                                class="form-control">
                                            @error('count')
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Əsas şəkil</label>
                                            <input type="file" class="form-control" accept=".png,.jpg,.jpeg,.svg"
                                                name="image">
                                            <div class="upload-container mt-3 row">
                                                <div class="mb-3 col-sm-6 col-md-3">
                                                    <div class="upload-image">
                                                        <img src="{{ asset($product->image) }}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            @error('image')
                                                <div class="invalid-feedback" style="display: block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Şəkillər</label>
                                            <input type="file" class="form-control" multiple
                                                accept=".png,.jpg,.jpeg,.svg" name="images[]">
                                            <div class="upload-container mt-3 row">
                                                @foreach ($product->images as $image)
                                                    <div class="col-md-3 col-sm-6 mb-3">
                                                        <div class="upload-image">
                                                            <img src="{{ asset($image->image) }}" alt="">
                                                            <i class="mdi mdi-close"></i>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('images')
                                                <div class="invalid-feedback" style="display: block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="mb-3">Kateqoriya</div>
                                        <select class="select2 form-control select2-multiple" name="category_id">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback" style="display: block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="mb-3">Alt kateqoriya</div>
                                        <select class="select2 form-control select2-multiple" name="sub_category_id">
                                            @foreach ($sub_categories as $sub_category)
                                                <option value="{{ $sub_category->id }}"
                                                    {{ $product->sub_category_id == $sub_category->id ? 'selected' : '' }}>
                                                    {{ $sub_category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('sub_category_id')
                                            <div class="invalid-feedback" style="display: block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-primary" type="submit">Təsdiqlə</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end card -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="{{ asset('back/assets') }}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="{{ asset('back/assets') }}/libs/select2/js/select2.min.js"></script>
    <script src="{{ asset('back/assets/js/pages/file-upload.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".summernote").summernote();
            $('.dropdown-toggle').dropdown();
        });
    </script>
    <!-- //Summernote JS - CDN Link -->

    <script>
        $('select').select2();
    </script>

    <script>
        $('[name="category_id"]').on('change', function() {
            let id = $(this).val();
            let url = `/admin/product/${id}/get-sub-category`;
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    $('[name="sub_category_id"]').html(data.view);
                });
        });
    </script>
@endpush
