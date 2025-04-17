@extends('back.layouts.master')
@section('title', 'Hakkımızda Metin Bölümleri')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Haqqımızda Mətin</h4>
                        <!-- <p class="card-category">Bu </p> -->
                    </div>
                    <div class="card-body">
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

                        <form action="{{ route('back.pages.about-text-sections.update') }}" method="POST" id="aboutTextSectionForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" name="status" {{ $aboutTextSection->status ? 'checked' : '' }}>
                                                <span class="toggle"></span>
                                                Aktiv
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bölüm 1 -->
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Bölüm 1</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="section1Tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="section1-az-tab" data-toggle="tab" href="#section1-az" role="tab" aria-controls="section1-az" aria-selected="true">Az</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="section1-en-tab" data-toggle="tab" href="#section1-en" role="tab" aria-controls="section1-en" aria-selected="false">En</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="section1-ru-tab" data-toggle="tab" href="#section1-ru" role="tab" aria-controls="section1-ru" aria-selected="false">Ru</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="section1TabsContent">
                                        <div class="tab-pane fade show active" id="section1-az" role="tabpanel" aria-labelledby="section1-az-tab">
                                            <div class="form-group">
                                                <label for="title1_az">Başliq (AZ)</label>
                                                <input type="text" class="form-control" id="title1_az" name="title1_az" value="{{ old('title1_az', $aboutTextSection->title1_az) }}">
                                                @error('title1_az')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="description1_az">Məlumat (AZ)</label>
                                                <textarea class="form-control" id="description1_az" name="description1_az" rows="5">{{ old('description1_az', $aboutTextSection->description1_az) }}</textarea>
                                                @error('description1_az')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="section1-en" role="tabpanel" aria-labelledby="section1-en-tab">
                                            <div class="form-group">
                                                <label for="title1_en">Başliq (EN)</label>
                                                <input type="text" class="form-control" id="title1_en" name="title1_en" value="{{ old('title1_en', $aboutTextSection->title1_en) }}">
                                                @error('title1_en')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="description1_en">Məlumat (EN)</label>
                                                <textarea class="form-control" id="description1_en" name="description1_en" rows="5">{{ old('description1_en', $aboutTextSection->description1_en) }}</textarea>
                                                @error('description1_en')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="section1-ru" role="tabpanel" aria-labelledby="section1-ru-tab">
                                            <div class="form-group">
                                                <label for="title1_ru">Başliq (RU)</label>
                                                <input type="text" class="form-control" id="title1_ru" name="title1_ru" value="{{ old('title1_ru', $aboutTextSection->title1_ru) }}">
                                                @error('title1_ru')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="description1_ru">Məlumat (RU)</label>
                                                <textarea class="form-control" id="description1_ru" name="description1_ru" rows="5">{{ old('description1_ru', $aboutTextSection->description1_ru) }}</textarea>
                                                @error('description1_ru')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bölüm 2 -->
                            <div class="card mt-4">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Bölüm 2</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="section2Tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="section2-az-tab" data-toggle="tab" href="#section2-az" role="tab" aria-controls="section2-az" aria-selected="true">Az</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="section2-en-tab" data-toggle="tab" href="#section2-en" role="tab" aria-controls="section2-en" aria-selected="false">En</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="section2-ru-tab" data-toggle="tab" href="#section2-ru" role="tab" aria-controls="section2-ru" aria-selected="false">Ru</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="section2TabsContent">
                                        <div class="tab-pane fade show active" id="section2-az" role="tabpanel" aria-labelledby="section2-az-tab">
                                            <div class="form-group">
                                                <label for="title2_az">Başliq (AZ)</label>
                                                <input type="text" class="form-control" id="title2_az" name="title2_az" value="{{ old('title2_az', $aboutTextSection->title2_az) }}">
                                                @error('title2_az')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="description2_az">Məlumat (AZ)</label>
                                                <textarea class="form-control" id="description2_az" name="description2_az" rows="5">{{ old('description2_az', $aboutTextSection->description2_az) }}</textarea>
                                                @error('description2_az')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="section2-en" role="tabpanel" aria-labelledby="section2-en-tab">
                                            <div class="form-group">
                                                <label for="title2_en">Başliq (EN)</label>
                                                <input type="text" class="form-control" id="title2_en" name="title2_en" value="{{ old('title2_en', $aboutTextSection->title2_en) }}">
                                                @error('title2_en')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="description2_en">Məlumat (EN)</label>
                                                <textarea class="form-control" id="description2_en" name="description2_en" rows="5">{{ old('description2_en', $aboutTextSection->description2_en) }}</textarea>
                                                @error('description2_en')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="section2-ru" role="tabpanel" aria-labelledby="section2-ru-tab">
                                            <div class="form-group">
                                                <label for="title2_ru">Başliq (RU)</label>
                                                <input type="text" class="form-control" id="title2_ru" name="title2_ru" value="{{ old('title2_ru', $aboutTextSection->title2_ru) }}">
                                                @error('title2_ru')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="description2_ru">Məlumat (RU)</label>
                                                <textarea class="form-control" id="description2_ru" name="description2_ru" rows="5">{{ old('description2_ru', $aboutTextSection->description2_ru) }}</textarea>
                                                @error('description2_ru')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bölüm 3 -->
                            <div class="card mt-4">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Bölüm 3</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="section3Tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="section3-az-tab" data-toggle="tab" href="#section3-az" role="tab" aria-controls="section3-az" aria-selected="true">Az</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="section3-en-tab" data-toggle="tab" href="#section3-en" role="tab" aria-controls="section3-en" aria-selected="false">En</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="section3-ru-tab" data-toggle="tab" href="#section3-ru" role="tab" aria-controls="section3-ru" aria-selected="false">Ru</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="section3TabsContent">
                                        <div class="tab-pane fade show active" id="section3-az" role="tabpanel" aria-labelledby="section3-az-tab">
                                            <div class="form-group">
                                                <label for="title3_az">Başliq (AZ)</label>
                                                <input type="text" class="form-control" id="title3_az" name="title3_az" value="{{ old('title3_az', $aboutTextSection->title3_az) }}">
                                                @error('title3_az')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="description3_az">Məlumat (AZ)</label>
                                                <textarea class="form-control" id="description3_az" name="description3_az" rows="5">{{ old('description3_az', $aboutTextSection->description3_az) }}</textarea>
                                                @error('description3_az')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="section3-en" role="tabpanel" aria-labelledby="section3-en-tab">
                                            <div class="form-group">
                                                <label for="title3_en">Başliq (EN)</label>
                                                <input type="text" class="form-control" id="title3_en" name="title3_en" value="{{ old('title3_en', $aboutTextSection->title3_en) }}">
                                                @error('title3_en')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="description3_en">Məlumat (EN)</label>
                                                <textarea class="form-control" id="description3_en" name="description3_en" rows="5">{{ old('description3_en', $aboutTextSection->description3_en) }}</textarea>
                                                @error('description3_en')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="section3-ru" role="tabpanel" aria-labelledby="section3-ru-tab">
                                            <div class="form-group">
                                                <label for="title3_ru">Başliq (RU)</label>
                                                <input type="text" class="form-control" id="title3_ru" name="title3_ru" value="{{ old('title3_ru', $aboutTextSection->title3_ru) }}">
                                                @error('title3_ru')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="description3_ru">Məlumat (RU)</label>
                                                <textarea class="form-control" id="description3_ru" name="description3_ru" rows="5">{{ old('description3_ru', $aboutTextSection->description3_ru) }}</textarea>
                                                @error('description3_ru')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Yenilə</button>
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
        // Tab'lar arasındaki geçişi düzelt
        $('#section1-az-tab').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        $('#section1-en-tab').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        $('#section1-ru-tab').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        $('#section2-az-tab').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        $('#section2-en-tab').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        $('#section2-ru-tab').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        $('#section3-az-tab').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        $('#section3-en-tab').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        $('#section3-ru-tab').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endpush 