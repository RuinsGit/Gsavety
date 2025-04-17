@extends('back.layouts.master')

@section('content')
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="margin-top: 100px;">
                <h5 class="mb-0">Servis Hero edit</h5>
                <a href="{{ route('back.pages.service-heroes.index') }}" class="btn btn-secondary">
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

                <form action="{{ route('back.pages.service-heroes.update', $serviceHero->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label for="image" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="image" name="image">
                        
                        @if ($serviceHero->image)
                            <div class="mt-2">
                                <img src="{{ asset($serviceHero->image) }}" alt="Mevcut Resim" class="img-thumbnail" style="max-height: 150px">
                                <p class="text-muted">Movcud foto</p>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="title_az" class="form-label">Başliq (AZ) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title_az" name="title_az" value="{{ old('title_az', $serviceHero->title_az) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="title_en" class="form-label">Başliq (EN) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title_en" name="title_en" value="{{ old('title_en', $serviceHero->title_en) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="title_ru" class="form-label">Başliq (RU) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title_ru" name="title_ru" value="{{ old('title_ru', $serviceHero->title_ru) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="image_alt_az" class="form-label">Foto Alt (AZ) <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="image_alt_az" name="image_alt_az" rows="3" required>{{ old('image_alt_az', $serviceHero->image_alt_az) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="image_alt_en" class="form-label">Foto Alt (EN) <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="image_alt_en" name="image_alt_en" rows="3" required>{{ old('image_alt_en', $serviceHero->image_alt_en) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="image_alt_ru" class="form-label">Foto Alt (RU) <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="image_alt_ru" name="image_alt_ru" rows="3" required>{{ old('image_alt_ru', $serviceHero->image_alt_ru) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="order" class="form-label">Sıra</label>
                                <input type="number" class="form-control" id="order" name="order" value="{{ old('order', $serviceHero->order) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1" {{ old('status', $serviceHero->status) == '1' ? 'selected' : '' }}>Aktiv</option>
                                    <option value="0" {{ old('status', $serviceHero->status) == '0' ? 'selected' : '' }}>Deaktiv</option>
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