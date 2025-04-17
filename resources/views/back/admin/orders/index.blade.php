@extends('back.layouts.master')

@section('title', 'Siparişler')

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
                    <h4 class="mb-sm-0">Sifarişlər</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana sayfa</a></li>
                            <li class="breadcrumb-item active">Sifarişlər</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title">Bütün Sifarişlər</h4>
                            <div>
                                <a href="{{ route('back.pages.orders.export') }}" class="btn btn-success waves-effect waves-light">
                                    <i class="ri-file-excel-line align-middle me-1"></i> Çıxarış (CSV)
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="orders-table" class="table table-bordered table-hover dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sifariş No</th>
                                        <th>Tarix</th>
                                        <th>Müştəri</th>
                                        <th>E-poçt</th>
                                        <th>Telefon</th>
                                        <th>Cəmi</th>
                                        <th>Status</th>
                                        <th>Ödəniş</th>
                                        <th>İşləmlər</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                        <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>{{ $order->phone }}</td>
                                        <td>{{ number_format($order->total_amount, 2) }} ₼</td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning">Beklemede</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge bg-info">İşleniyor</span>
                                            @elseif($order->status == 'completed')
                                                <span class="badge bg-success">Tamamlandı</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge bg-danger">İptal Edildi</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->payment_status == 'pending')
                                                <span class="badge bg-warning">Beklemede</span>
                                            @elseif($order->payment_status == 'paid')
                                                <span class="badge bg-success">Ödendi</span>
                                            @elseif($order->payment_status == 'failed')
                                                <span class="badge bg-danger">Başarısız</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('back.pages.orders.show', $order->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Görüntüle">
                                                <i class="mdi mdi-eye font-size-16"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $order->id }}">
                                                <i class="mdi mdi-trash-can font-size-16"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silme Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Razılıq</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bu sifarişi silmek istediyinize əminsiniz? Bu geri alına bilməz!
            </div>
            <div class="modal-footer">
                <form action="" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İmtina</button>
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // DataTable
        $('#orders-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Turkish.json"
            },
            "pageLength": 25,
            "order": [[ 0, "desc" ]],
            "columnDefs": [
                { "orderable": false, "targets": 9 }
            ]
        });
        
        // Tooltipleri etkinleştir
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Silme işlemi
        $('.delete-btn').on('click', function() {
            var id = $(this).data('id');
            
            Swal.fire({
                title: 'Silmek istediğinize emin misiniz?',
                text: "Bu işlem geri alınamaz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteForm').attr('action', '{{ route("back.pages.orders.destroy", "") }}/' + id);
                    $('#deleteForm').submit();
                }
            });
        });
    });
</script>
@endpush 