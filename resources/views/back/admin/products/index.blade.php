@extends('back.layouts.master')

@section('title', 'Məhsullar')

@section('content')
    <style>
        .swal2-popup {
            border-radius: 50px;
        }
    </style>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "{{ session('success') }}",
                    showConfirmButton: true,
                    confirmButtonText: 'Tamam',
                    timer: 1500
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "{{ session('error') }}",
                    showConfirmButton: true,
                    confirmButtonText: 'Tamam',
                    timer: 1500
                });
            });
        </script>
    @endif

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Məhsullar</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Səhifə</a></li>
                                <li class="breadcrumb-item active">Məhsullar</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12 d-flex justify-content-end mb-4">
                                <a href="{{ route('back.pages.products.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Yeni
                                </a>
                            </div>

                            <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#az" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block" style=" color: #ff8a33;">AZ</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#en" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block" style=" color: #ff8a33;">EN</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#ru" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block" style=" color: #ff8a33;">RU</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="az" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Şəkil</th>
                                                    <th>Referans</th>
                                                    <th>SKU</th>
                                                    <th>Məhsul Adı (AZ)</th>
                                                    <th>Qiymət</th>
                                                    <th>Endirimli Qiymət</th>
                                                    <th>Önə Çıxan</th>
                                                    <th>Status</th>
                                                    <th>Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($products as $product)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                @if($product->main_image)
                                                                    <img src="{{ asset($product->main_image) }}" alt="{{ $product->name_az }}" class="product-img">
                                                                @else
                                                                    <span class="badge bg-danger">Şəkil Yoxdur</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $product->reference }}</td>
                                                        <td>{{ $product->sku }}</td>
                                                        <td>{{ $product->name_az }}</td>
                                                        <td>{{ $product->price }}</td>
                                                        <td>{{ $product->discount_price ?? '-' }}</td>
                                                        <td>
                                                            <div class="form-check form-switch form-switch-success">
                                                                <form action="{{ route('back.pages.products.toggle-featured', $product->id) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-check-input toggle-featured" type="checkbox" role="switch" id="featured_{{ $product->id }}" {{ $product->is_featured ? 'checked' : '' }} data-id="{{ $product->id }}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-switch form-switch-success">
                                                                <form action="{{ route('back.pages.products.toggle-status', $product->id) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-check-input toggle-status" type="checkbox" role="switch" id="status_{{ $product->id }}" {{ $product->status ? 'checked' : '' }} data-id="{{ $product->id }}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('back.pages.products.show', $product->id) }}" class="btn btn-info btn-sm" style="background-color: #3498db; border-color: #2980b9">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('back.pages.products.edit', $product->id) }}" class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form id="delete-form-{{ $product->id }}" action="{{ route('back.pages.products.destroy', $product->id) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteData({{ $product->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="en" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Şəkil</th>
                                                    <th>Referans</th>
                                                    <th>SKU</th>
                                                    <th>Məhsul Adı (EN)</th>
                                                    <th>Qiymət</th>
                                                    <th>Endirimli Qiymət</th>
                                                    <th>Önə Çıxan</th>
                                                    <th>Status</th>
                                                    <th>Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($products as $product)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                @if($product->main_image)
                                                                    <img src="{{ asset($product->main_image) }}" alt="{{ $product->name_en }}" class="product-img">
                                                                @else
                                                                    <span class="badge bg-danger">Şəkil Yoxdur</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $product->reference }}</td>
                                                        <td>{{ $product->sku }}</td>
                                                        <td>{{ $product->name_en }}</td>
                                                        <td>{{ $product->price }}</td>
                                                        <td>{{ $product->discount_price ?? '-' }}</td>
                                                        <td>
                                                            <div class="form-check form-switch form-switch-success">
                                                                <form action="{{ route('back.pages.products.toggle-featured', $product->id) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-check-input toggle-featured" type="checkbox" role="switch" id="featured_en_{{ $product->id }}" {{ $product->is_featured ? 'checked' : '' }} data-id="{{ $product->id }}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-switch form-switch-success">
                                                                <form action="{{ route('back.pages.products.toggle-status', $product->id) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-check-input toggle-status" type="checkbox" role="switch" id="status_en_{{ $product->id }}" {{ $product->status ? 'checked' : '' }} data-id="{{ $product->id }}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('back.pages.products.show', $product->id) }}" class="btn btn-info btn-sm" style="background-color: #3498db; border-color: #2980b9">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('back.pages.products.edit', $product->id) }}" class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form id="delete-form-en-{{ $product->id }}" action="{{ route('back.pages.products.destroy', $product->id) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteData({{ $product->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="ru" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Şəkil</th>
                                                    <th>Referans</th>
                                                    <th>SKU</th>
                                                    <th>Məhsul Adı (RU)</th>
                                                    <th>Qiymət</th>
                                                    <th>Endirimli Qiymət</th>
                                                    <th>Önə Çıxan</th>
                                                    <th>Status</th>
                                                    <th>Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($products as $product)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="image-preview">
                                                                @if($product->main_image)
                                                                    <img src="{{ asset($product->main_image) }}" alt="{{ $product->name_ru }}" class="product-img">
                                                                @else
                                                                    <span class="badge bg-danger">Şəkil Yoxdur</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $product->reference }}</td>
                                                        <td>{{ $product->sku }}</td>
                                                        <td>{{ $product->name_ru }}</td>
                                                        <td>{{ $product->price }}</td>
                                                        <td>{{ $product->discount_price ?? '-' }}</td>
                                                        <td>
                                                            <div class="form-check form-switch form-switch-success">
                                                                <form action="{{ route('back.pages.products.toggle-featured', $product->id) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-check-input toggle-featured" type="checkbox" role="switch" id="featured_ru_{{ $product->id }}" {{ $product->is_featured ? 'checked' : '' }} data-id="{{ $product->id }}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-switch form-switch-success">
                                                                <form action="{{ route('back.pages.products.toggle-status', $product->id) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-check-input toggle-status" type="checkbox" role="switch" id="status_ru_{{ $product->id }}" {{ $product->status ? 'checked' : '' }} data-id="{{ $product->id }}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('back.pages.products.show', $product->id) }}" class="btn btn-info btn-sm" style="background-color: #3498db; border-color: #2980b9">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('back.pages.products.edit', $product->id) }}" class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form id="delete-form-ru-{{ $product->id }}" action="{{ route('back.pages.products.destroy', $product->id) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteData({{ $product->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .image-preview {
        width: 100px;
        height: 100px;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 0 auto;
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.3s ease;
    }

    .image-preview:hover .product-img {
        transform: scale(1.05);
    }

    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        border-radius: 12px;
        overflow: hidden;
    }

    .nav-tabs {
        border-bottom: 2px solid #eee;
        margin-bottom: 20px;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
        transition: all 0.2s ease;
    }

    .nav-tabs .nav-link.active {
        color: #2c3e50;
        border-bottom: 2px solid #3498db;
        background: transparent;
    }

    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #3498db;
    }
    </style>
@endsection

@push('js')
<script>
    function deleteData(id) {
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
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    $(document).ready(function() {
        $('.toggle-status').change(function() {
            var id = $(this).data('id');
            var form = $(this).closest('form');
            form.submit();
        });

        $('.toggle-featured').change(function() {
            var id = $(this).data('id');
            var form = $(this).closest('form');
            form.submit();
        });

        $('.table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
            }
        });
    });
</script>
@endpush 