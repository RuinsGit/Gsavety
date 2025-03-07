@extends('back.layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Ümumi tənzimləmələr</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                                <li class="breadcrumb-item active">Ümumi tənzimləmələr</li>
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
                            <h4 class="card-title">Ümumi tənzimləmələr</h4>
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
                            <form class="needs-validation" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ $contact?->email }}">
                                            @error('email')
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Telefon nömrəsi</label>
                                            <input type="text" class="form-control" name="phone"
                                                value="{{ $contact?->phone }}">
                                            @error('phone')
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="az">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Ünvan (Az)</label>
                                                    <input type="text" name="address_az"
                                                        value="{{ $contact?->address_az }}" class="form-control">
                                                    @error('address_az')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">İş saatları (Az)</label>
                                                    <textarea name="work_hours_az" class="form-control">{{ $contact?->work_hours_az }}</textarea>
                                                    @error('work_hours_az')
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
                                                    <label for="" class="form-label">Ünvan (En)</label>
                                                    <input type="text" name="address_en"
                                                        value="{{ $contact?->address_en }}" class="form-control">
                                                    @error('address_en')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">İş saatları (En)</label>
                                                    <textarea name="work_hours_en" class="form-control">{{ $contact?->work_hours_en }}</textarea>
                                                    @error('work_hours_en')
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
                                                    <label for="" class="form-label">Ünvan (Ru)</label>
                                                    <input type="text" name="address_ru"
                                                        value="{{ $contact?->address_ru }}" class="form-control">
                                                    @error('address_ru')
                                                        <div class="invalid-feedback" style="display: block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">İş saatları (Ru)</label>
                                                    <textarea name="work_hours_ru" class="form-control">{{ $contact?->work_hours_ru }}</textarea>
                                                    @error('work_hours_ru')
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
                                            <label for="" class="form-label">Facebook</label>
                                            <input type="text" class="form-control" name="facebook"
                                                value="{{ $contact?->facebook }}">
                                            @error('facebook')
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">İnstagram</label>
                                            <input type="text" class="form-control" name="instagram"
                                                value="{{ $contact?->instagram }}">
                                            @error('instagram')
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Youtube</label>
                                            <input type="text" class="form-control" name="youtube"
                                                value="{{ $contact?->youtube }}">
                                            @error('youtube')
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Loqo</label>
                                            <input type="file" name="logo" class="form-control"
                                                accept=".png,.jpg,.svg,.webp">
                                            <div class="upload-container mt-3 row">
                                                @if (!is_null($contact?->logo))
                                                    <div class="mb-3 col-sm-6 col-md-3">
                                                        <div class="upload-image">
                                                            <img src="{{ asset($contact?->logo) }}" alt="">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            @error('logo')
                                                <div class="invalid-feedback" style="display: block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Loqo 2 </label>
                                            <input type="file" name="logo_2" class="form-control"
                                                accept=".png,.jpg,.svg,.webp">
                                            <div class="upload-container mt-3 row">
                                                @if (!is_null($contact?->logo_2))
                                                    <div class="mb-3 col-sm-6 col-md-3">
                                                        <div class="upload-image">
                                                            <img src="{{ asset($contact?->logo_2) }}" alt="">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            @error('logo_2')
                                                <div class="invalid-feedback" style="display: block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Favikon</label>
                                            <input type="file" name="favicon" class="form-control"
                                                accept=".png,.jpg,.svg,.webp">
                                            <div class="upload-container mt-3 row">
                                                @if (!is_null($contact?->favicon))
                                                    <div class="mb-3 col-sm-6 col-md-3">
                                                        <div class="upload-image">
                                                            <img src="{{ asset($contact?->favicon) }}" alt="">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            @error('favicon')
                                                <div class="invalid-feedback" style="display: block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
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

@push('js')
    <script src="{{ asset('back/assets/js/pages/file-upload.js') }}"></script>
@endpush
