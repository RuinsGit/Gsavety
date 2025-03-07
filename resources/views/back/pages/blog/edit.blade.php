@extends('back.layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Xəbərlər</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Xəbərlər</a></li>
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
                            <h4 class="card-title">Xəbər redaktə et</h4>
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
                                action="{{ route('admin.blog.update', ['id' => $blog->id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content p-3 text-muted">
                                    <div class="tab-pane active" id="az">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="validationCustom03" class="form-label">Başlıq (Az)</label>
                                                <input type="text" name="title_az" value="{{ $blog->title_az }}"
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
                                                <label for="validationCustom03" class="form-label">Şəkil başlıq (Az)</label>
                                                <input type="text" name="image_title_az"
                                                    value="{{ $blog->image_title_az }}" class="form-control">
                                                @error('image_title_az')
                                                    <div class="invalid-feedback" style="display: block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="validationCustom03" class="form-label">Şəkil alt (Az)</label>
                                                <input type="text" name="image_alt_az" value="{{ $blog->image_alt_az }}"
                                                    class="form-control">
                                                @error('image_alt_az')
                                                    <div class="invalid-feedback" style="display: block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="validationCustom03" class="form-label">Meta title (Az)</label>
                                                <input type="text" name="meta_title_az"
                                                    value="{{ $blog->meta_title_az }}" class="form-control">
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
                                                    value="{{ $blog->meta_description_az }}" class="form-control">
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
                                                <textarea name="description_az" class="summernote form-control">{{ $blog->description_az }}</textarea>
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
                                                <label for="validationCustom03" class="form-label">Başlıq (En)</label>
                                                <input type="text" name="title_en" value="{{ $blog->title_en }}"
                                                    class="form-control">
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
                                                    value="{{ $blog->image_title_en }}" class="form-control">
                                                @error('image_title_en')
                                                    <div class="invalid-feedback" style="display: block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="validationCustom03" class="form-label">Şəkil alt (En)</label>
                                                <input type="text" name="image_alt_en"
                                                    value="{{ $blog->image_alt_en }}" class="form-control">
                                                @error('image_alt_en')
                                                    <div class="invalid-feedback" style="display: block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="validationCustom03" class="form-label">Meta title (En)</label>
                                                <input type="text" name="meta_title_en"
                                                    value="{{ $blog->meta_title_en }}" class="form-control">
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
                                                    value="{{ $blog->meta_description_en }}" class="form-control">
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
                                                <textarea name="description_en" class="summernote form-control">{{ $blog->description_en }}</textarea>
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
                                                <input type="text" name="title_ru" value="{{ $blog->title_ru }}"
                                                    class="form-control">
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
                                                    value="{{ $blog->image_title_ru }}" class="form-control">
                                                @error('image_title_ru')
                                                    <div class="invalid-feedback" style="display: block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="validationCustom03" class="form-label">Şəkil alt (Ru)</label>
                                                <input type="text" name="image_alt_ru"
                                                    value="{{ $blog->image_alt_ru }}" class="form-control">
                                                @error('image_alt_ru')
                                                    <div class="invalid-feedback" style="display: block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="validationCustom03" class="form-label">Meta title (Ru)</label>
                                                <input type="text" name="meta_title_ru"
                                                    value="{{ $blog->meta_title_ru }}" class="form-control">
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
                                                    value="{{ $blog->meta_description_ru }}" class="form-control">
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
                                                <textarea name="description_ru" class="summernote form-control">{{ $blog->description_ru }}</textarea>
                                                @error('description_ru')
                                                    <div class="invalid-feedback" style="display: block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Tarix</label>
                                        <input type="date" name="date" value="{{ $blog->date->format('Y-m-d') }}"
                                            class="form-control">
                                        @error('date')
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
                                            name="poster_image">
                                        <div class="upload-container mt-3 row">
                                            <div class="mb-3 col-sm-6 col-md-3">
                                                <div class="upload-image">
                                                    <img src="{{ asset($blog->poster_image) }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        @error('poster_image')
                                            <div class="invalid-feedback" style="display: block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Şəkil</label>
                                        <input type="file" class="form-control" accept=".png,.jpg,.jpeg,.svg"
                                            name="image">
                                        <div class="upload-container mt-3 row">
                                            <div class="mb-3 col-sm-6 col-md-3">
                                                <div class="upload-image">
                                                    <img src="{{ asset($blog->image) }}" alt="">
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
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">Təsdiqlə</button>
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
    <script src="{{ asset('back/assets') }}/js/pages/file-upload.js"></script>
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
@endpush
