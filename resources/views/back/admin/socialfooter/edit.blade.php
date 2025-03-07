@extends('back.layouts.master')
@section('title', 'Sosial Media Redaktə')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Sosial Media Redaktə</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('back.pages.socialfooter.update', $socialfooter->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="image">İkon</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($socialfooter->image)
                                            <div class="mt-2">
                                                <img src="{{ asset($socialfooter->image) }}" alt="Current Image" style="height: 100px;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="link">Link</label>
                                        <input type="url" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link', $socialfooter->link) }}" required>
                                        @error('link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <!-- <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status" name="status" {{ $socialfooter->status ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status">Status</label>
                                        </div>
                                    </div> -->
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Yadda saxla</button>
                                        <a href="{{ route('back.pages.socialfooter.index') }}" class="btn btn-secondary">Geri qayıt</a>
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