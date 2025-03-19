@extends('back.layouts.master')

@section('title', '�laqə Redaktə')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Əlaqə Redaktə</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('back.pages.contact.index') }}">Əlaqə</a></li>
                        <li class="breadcrumb-item active">Əlaqə Redaktə</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <form action="{{ route('back.pages.contact.update', $contact->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nömrə</label>
                                            <input type="text" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number', $contact->number) }}" required>
                                            @error('number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nömrə Şəkli</label>
                                            <input type="file" name="number_image" class="form-control @error('number_image') is-invalid @enderror">
                                            @error('number_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if($contact->number_image)
                                            <div class="mt-2">
                                                <img src="{{ asset($contact->number_image) }}" alt="" class="img-fluid" style="max-height: 100px">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">E-poçt</label>
                                            <input type="email" name="mail" class="form-control @error('mail') is-invalid @enderror" value="{{ old('mail', $contact->mail) }}" required>
                                            @error('mail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">E-poçt Şəkli</label>
                                            <input type="file" name="mail_image" class="form-control @error('mail_image') is-invalid @enderror">
                                            @error('mail_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if($contact->mail_image)
                                            <div class="mt-2">
                                                <img src="{{ asset($contact->mail_image) }}" alt="" class="img-fluid" style="max-height: 100px">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Ünvan Şəkli</label>
                                            <input type="file" name="address_image" class="form-control @error('address_image') is-invalid @enderror">
                                            @error('address_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if($contact->address_image)
                                            <div class="mt-2">
                                                <img src="{{ asset($contact->address_image) }}" alt="" class="img-fluid" style="max-height: 100px">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <ul class="nav nav-tabs" id="langTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="az-tab" data-bs-toggle="tab" data-bs-target="#az" type="button" role="tab" aria-controls="az" aria-selected="true">AZ</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="en-tab" data-bs-toggle="tab" data-bs-target="#en" type="button" role="tab" aria-controls="en" aria-selected="false">EN</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="ru-tab" data-bs-toggle="tab" data-bs-target="#ru" type="button" role="tab" aria-controls="ru" aria-selected="false">RU</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="langTabsContent">
                                    <div class="tab-pane fade show active" id="az" role="tabpanel" aria-labelledby="az-tab">
                                        <div class="mb-3">
                                            <label class="form-label">Ünvan (AZ)</label>
                                            <input type="text" name="address_az" class="form-control @error('address_az') is-invalid @enderror" value="{{ old('address_az', $contact->address_az) }}" required>
                                            @error('address_az')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="mb-3">
                                            <label class="form-label">Ünvan (EN)</label>
                                            <input type="text" name="address_en" class="form-control @error('address_en') is-invalid @enderror" value="{{ old('address_en', $contact->address_en) }}" required>
                                            @error('address_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="ru" role="tabpanel" aria-labelledby="ru-tab">
                                        <div class="mb-3">
                                            <label class="form-label">Ünvan (RU)</label>
                                            <input type="text" name="address_ru" class="form-control @error('address_ru') is-invalid @enderror" value="{{ old('address_ru', $contact->address_ru) }}" required>
                                            @error('address_ru')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Waze navigasiya linki</label>
                                    <textarea name="filial_description" class="form-control @error('filial_description') is-invalid @enderror" rows="3">{{ old('filial_description', $contact->filial_description) }}</textarea>
                                    @error('filial_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Yadda Saxla</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 