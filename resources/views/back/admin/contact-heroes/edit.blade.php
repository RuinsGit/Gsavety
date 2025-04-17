@extends('back.layouts.master')

@section('content')
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="margin-top: 100px;">
                <h5 class="mb-0">Əlaqə Hero Redaktə</h5>
                <a href="{{ route('back.pages.contact-heroes.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Geri Dön
                </a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('back.pages.contact-heroes.update', $contactHero->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label for="image" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="image" name="image">
                        
                        @if ($contactHero->image)
                            <div class="mt-2">
                                <img src="{{ asset($contactHero->image) }}" alt="Mevcut Resim" class="img-thumbnail" style="max-height: 150px">
                                <p class="text-muted">Mövcud foto</p>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="title_az" class="form-label">Başlıq (AZ) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title_az" name="title_az" value="{{ old('title_az', $contactHero->title_az) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="title_en" class="form-label">Başlıq (EN) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title_en" name="title_en" value="{{ old('title_en', $contactHero->title_en) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="title_ru" class="form-label">Başlıq (RU) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title_ru" name="title_ru" value="{{ old('title_ru', $contactHero->title_ru) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="image_alt_az" class="form-label">Foto Alt (AZ)</label>
                                <input type="text" class="form-control" id="image_alt_az" name="image_alt_az" value="{{ old('image_alt_az', $contactHero->image_alt_az) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="image_alt_en" class="form-label">Foto Alt (EN)</label>
                                <input type="text" class="form-control" id="image_alt_en" name="image_alt_en" value="{{ old('image_alt_en', $contactHero->image_alt_en) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="image_alt_ru" class="form-label">Foto Alt (RU)</label>
                                <input type="text" class="form-control" id="image_alt_ru" name="image_alt_ru" value="{{ old('image_alt_ru', $contactHero->image_alt_ru) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="order" class="form-label">Sıra</label>
                                <input type="number" class="form-control" id="order" name="order" value="{{ old('order', $contactHero->order) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1" {{ old('status', $contactHero->status) == '1' ? 'selected' : '' }}>Aktiv</option>
                                    <option value="0" {{ old('status', $contactHero->status) == '0' ? 'selected' : '' }}>Deaktiv</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Yenilə</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 