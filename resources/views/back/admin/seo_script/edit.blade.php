@extends('back.layouts.master')
@section('title', 'SEO Script Redaktə Et')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">SEO Script Redaktə Et</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('back.pages.seo_script.update', $seoScript->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Script Məzmunu</label>
                                        <textarea class="form-control @error('script_content') is-invalid @enderror" name="script_content" rows="10" required>{{ old('script_content', $seoScript->script_content) }}</textarea>
                                        <small class="text-muted">HTML, JavaScript və ya digər script kodlarını buraya əlavə edə bilərsiniz</small>
                                        @error('script_content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="d-block">Status</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ old('status', $seoScript->status) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusActive">Aktiv</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" {{ old('status', $seoScript->status) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusInactive">Deaktiv</label>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Güncellə</button>
                                        <a href="{{ route('back.pages.seo_script.index') }}" class="btn btn-secondary">Geri Dön</a>
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