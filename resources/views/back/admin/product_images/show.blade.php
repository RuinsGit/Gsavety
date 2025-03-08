@extends('back.layouts.master')

@section('title', 'Məhsul Şəkli Göstər')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Məhsul Şəkli Göstər</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.product_images.index') }}">Məhsul Şəkilləri</a></li>
                            <li class="breadcrumb-item active">Göstər</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

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

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="image-container mb-4">
                                    <img src="{{ asset($image->image_path) }}" alt="{{ $image->alt_text_az }}" class="img-fluid rounded" style="max-height: 300px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end mb-3">
                                    <a href="{{ route('back.pages.product_images.edit', $image->id) }}" class="btn btn-primary me-2">
                                        <i class="fas fa-edit"></i> Redaktə et
                                    </a>
                                    <form id="delete-form" action="{{ route('back.pages.product_images.destroy', $image->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                            <i class="fas fa-trash"></i> Sil
                                        </button>
                                    </form>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th style="width: 30%">Məhsul</th>
                                                <td>{{ $image->product->name_az }}</td>
                                            </tr>
                                            <tr>
                                                <th>Rəng</th>
                                                <td>{{ $image->color ? $image->color->color_name_az : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alt Mətn (AZ)</th>
                                                <td>{{ $image->alt_text_az ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alt Mətn (EN)</th>
                                                <td>{{ $image->alt_text_en ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alt Mətn (RU)</th>
                                                <td>{{ $image->alt_text_ru ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Əsas Şəkil</th>
                                                <td>
                                                    <span class="badge {{ $image->is_main ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $image->is_main ? 'Bəli' : 'Xeyr' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="badge {{ $image->status ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $image->status ? 'Aktiv' : 'Deaktiv' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Sıralama</th>
                                                <td>{{ $image->sort_order }}</td>
                                            </tr>
                                            <tr>
                                                <th>Yaradılma tarixi</th>
                                                <td>{{ $image->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Yenilənmə tarixi</th>
                                                <td>{{ $image->updated_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <a href="{{ route('back.pages.product_images.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Geri qayıt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Silmək istədiyinizdən əminsiniz?',
            text: "Bu əməliyyat geri alına bilməz!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Bəli, sil!',
            cancelButtonText: 'Xeyr'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
@endpush 