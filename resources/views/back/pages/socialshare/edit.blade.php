@extends('back.layouts.master')

@section('title', 'Sosial Media Redaktə')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sosial Media Redaktə</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('back.pages.socialshare.index') }}">Sosial Media</a></li>
                        <li class="breadcrumb-item active">Redaktə</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('back.pages.socialshare.update', $socialshare->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Ad</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $socialshare->name) }}" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">İkon (Şəkil)</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @if($socialshare->image)
                                <div class="mt-2">
                                    <img src="{{ asset($socialshare->image) }}" alt="{{ $socialshare->name }}" style="max-height: 50px;">
                                </div>
                            @endif
                            <small class="form-text text-muted">İkon üçün şəkil və ya SVG fayl yükləyin. Boş buraxsanız köhnə şəkil qalacaq.</small>
                        </div>

                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="text" name="link" id="link" class="form-control @error('link') is-invalid @enderror" value="{{ old('link', $socialshare->link) }}" required>
                            @error('link')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">Sıra</label>
                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $socialshare->order) }}" min="0">
                            @error('order')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', $socialshare->status) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">Status</label>
                            </div>
                        </div> -->

                        <button type="submit" class="btn btn-primary">Yadda saxla</button>
                        <a href="{{ route('back.pages.socialshare.index') }}" class="btn btn-secondary">Ləğv et</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush 