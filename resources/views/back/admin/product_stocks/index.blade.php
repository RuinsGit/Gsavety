@extends('back.layouts.master')

@section('title', 'Məhsul Stokları')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Məhsul Stokları</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item active">Məhsul Stokları</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Məhsul Stokları Siyahısı</h5>
                            <a href="{{ route('back.pages.product_stocks.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Yeni Stok Əlavə Et
                            </a>
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

                        <div class="table-responsive">
                            <table id="stock-table" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Məhsul</th>
                                        <th>Rəng</th>
                                        <th>Ölçü</th>
                                        <th>Stok Miqdarı</th>
                                        <th>SKU</th>
                                        <th>Status</th>
                                        <th>Əməliyyatlar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stocks as $key => $stock)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $stock->product->name_az }}</td>
                                        <td>{{ $stock->color ? $stock->color->color_name_az : '-' }}</td>
                                        <td>{{ $stock->size ? $stock->size->size_name_az : '-' }}</td>
                                        <td>{{ $stock->quantity }}</td>
                                        <td>{{ $stock->sku }}</td>
                                        <td>
                                            <form action="{{ route('back.pages.product_stocks.toggle-status', $stock->id) }}" method="POST" class="status-form">
                                                @csrf
                                                <div class="form-check form-switch form-switch-success" style="margin-left: 0;">
                                                    <input class="form-check-input status-switch" type="checkbox" id="status_{{ $stock->id }}" {{ $stock->status ? 'checked' : '' }} data-id="{{ $stock->id }}">
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('back.pages.product_stocks.show', $stock->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('back.pages.product_stocks.edit', $stock->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('back.pages.product_stocks.destroy', $stock->id) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $stock->id }}">
                                                        <i class="fas fa-trash"></i>
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
<script>
    $(document).ready(function() {
        $('#stock-table').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Turkish.json'
            }
        });

        // Status toggle
        $('.status-switch').change(function() {
            var id = $(this).data('id');
            $(this).closest('form').submit();
        });

        // Delete confirmation
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            var form = $(this).closest('form');
            
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
                    form.submit();
                }
            });
        });
    });
</script>
@endpush 