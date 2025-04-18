@extends('back.layouts.master')

@section('content')
    <div class="container-fluid p-4" style="margin-top: 100px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Əlaqə Başlığı Redaktə</h5>
                <a href="{{ route('back.pages.contact-titles.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Geri
                </a>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('back.pages.contact-titles.update', $contactTitle->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="title_az" class="form-label">Başlıq (AZ) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title_az" name="title_az" value="{{ old('title_az', $contactTitle->title_az) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="title_en" class="form-label">Başlıq (EN)</label>
                                <input type="text" class="form-control" id="title_en" name="title_en" value="{{ old('title_en', $contactTitle->title_en) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="title_ru" class="form-label">Başlıq (RU)</label>
                                <input type="text" class="form-control" id="title_ru" name="title_ru" value="{{ old('title_ru', $contactTitle->title_ru) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="special_title_az" class="form-label">Xüsusi Başlıq (AZ)</label>
                                <input type="text" class="form-control" id="special_title_az" name="special_title_az" value="{{ old('special_title_az', $contactTitle->special_title_az) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="special_title_en" class="form-label">Xüsusi Başlıq (EN)</label>
                                <input type="text" class="form-control" id="special_title_en" name="special_title_en" value="{{ old('special_title_en', $contactTitle->special_title_en) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="special_title_ru" class="form-label">Xüsusi Başlıq (RU)</label>
                                <input type="text" class="form-control" id="special_title_ru" name="special_title_ru" value="{{ old('special_title_ru', $contactTitle->special_title_ru) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="description_az" class="form-label">Məlumat (AZ)</label>
                                <textarea class="form-control" id="description_az" name="description_az" rows="5">{{ old('description_az', $contactTitle->description_az) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="description_en" class="form-label">Məlumat (EN)</label>
                                <textarea class="form-control" id="description_en" name="description_en" rows="5">{{ old('description_en', $contactTitle->description_en) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="description_ru" class="form-label">Məlumat (RU)</label>
                                <textarea class="form-control" id="description_ru" name="description_ru" rows="5">{{ old('description_ru', $contactTitle->description_ru) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="order" class="form-label">Sıra</label>
                                <input type="number" class="form-control" id="order" name="order" value="{{ old('order', $contactTitle->order) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" {{ old('status', $contactTitle->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Aktiv</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Yadda Saxla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 