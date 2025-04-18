@extends('back.layouts.master')

@section('title', 'Product Bannerlar')

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
                confirmButtonText: 'Yaxşı',
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
                confirmButtonText: 'Yaxşı',
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
                    <h4 class="mb-sm-0">Product Bannerlar</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item active">Product Bannerlar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <h4 class="card-title">Bannerlar Siyahısı</h4>
                            </div>
                            <div class="col-md-8 text-end">
                                <a href="{{ route('back.pages.product-banner.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus me-1"></i> Yeni Banner
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Şəkil</th>
                                        <th>Başlıq (AZ)</th>
                                        <th>Status</th>
                                        <th>Əməliyyatlar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($banners as $banner)
                                    <tr>
                                        <td>{{ $banner->id }}</td>
                                        <td>
                                            @if($banner->image)
                                                <img src="{{ asset($banner->image) }}" alt="{{ $banner->title_az }}" width="100" class="img-thumbnail">
                                            @else
                                                <span class="badge bg-warning">Şəkil yoxdur</span>
                                            @endif
                                        </td>
                                        <td>{{ $banner->title_az }}</td>
                                        <td>
                                            @if($banner->status)
                                                <span class="badge bg-success">Aktiv</span>
                                            @else
                                                <span class="badge bg-danger">Deaktiv</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('back.pages.product-banner.edit', $banner->id) }}" class="btn btn-info btn-sm">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>
                                                <form action="{{ route('back.pages.product-banner.destroy', $banner->id) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm delete-btn">
                                                        <i class="ri-delete-bin-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
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

@endsection

@push('js')
<!-- Required datatable js -->
<script src="{{ asset('back/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('back/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<!-- Responsive datatable -->
<script src="{{ asset('back/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('back/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/az.json',
            },
            "columnDefs": [
                { "orderable": false, "targets": [1, 4] }
            ]
        });

        // Silme işlemi için onay penceresi
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: "Əminsiniz?",
                text: "Bu banner silinəcək. Bu əməliyyat geri qaytarıla bilməz!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Bəli, sil!",
                cancelButtonText: "Ləğv et"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush 