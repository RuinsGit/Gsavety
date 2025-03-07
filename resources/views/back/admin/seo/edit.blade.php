@extends('back.layouts.master')
@section('title', 'SEO Düzənlə')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">SEO Düzənlə</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('back.pages.seo.update', $seo->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label" for="key">Key</label>
                                        <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" name="key" value="{{ old('key', $seo->key) }}" readonly required>
                                        @error('key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Meta Title (AZ)</label>
                                        <input type="text" class="form-control @error('meta_title_az') is-invalid @enderror" name="meta_title_az" value="{{ old('meta_title_az', $seo->meta_title_az) }}" required>
                                        @error('meta_title_az')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Meta Title (EN)</label>
                                        <input type="text" class="form-control @error('meta_title_en') is-invalid @enderror" name="meta_title_en" value="{{ old('meta_title_en', $seo->meta_title_en) }}" required>
                                        @error('meta_title_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Meta Title (RU)</label>
                                        <input type="text" class="form-control @error('meta_title_ru') is-invalid @enderror" name="meta_title_ru" value="{{ old('meta_title_ru', $seo->meta_title_ru) }}" required>
                                        @error('meta_title_ru')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Meta Description (AZ)</label>
                                        <textarea class="form-control @error('meta_description_az') is-invalid @enderror" name="meta_description_az" rows="3" required>{{ old('meta_description_az', $seo->meta_description_az) }}</textarea>
                                        @error('meta_description_az')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Meta Description (EN)</label>
                                        <textarea class="form-control @error('meta_description_en') is-invalid @enderror" name="meta_description_en" rows="3" required>{{ old('meta_description_en', $seo->meta_description_en) }}</textarea>
                                        @error('meta_description_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Meta Description (RU)</label>
                                        <textarea class="form-control @error('meta_description_ru') is-invalid @enderror" name="meta_description_ru" rows="3" required>{{ old('meta_description_ru', $seo->meta_description_ru) }}</textarea>
                                        @error('meta_description_ru')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="d-block">Status</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ old('status', $seo->status) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusActive">Aktiv</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" {{ old('status', $seo->status) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusInactive">Deaktiv</label>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Yadda saxla</button>
                                        <a href="{{ route('back.pages.seo.index') }}" class="btn btn-secondary">Geri qayıt</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
