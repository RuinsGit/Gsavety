@extends('back.layouts.master')

@section('title', 'Sipariş Detayı')

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
                    <h4 class="mb-sm-0">Sifariş  Detayı</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.orders.index') }}">Sifarişlər</a></li>
                            <li class="breadcrumb-item active">Sifariş Detayı</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">Sifariş #{{ $order->order_number }}</h5>
                            <div class="flex-shrink-0">
                                <a href="{{ route('back.pages.orders.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="ri-arrow-left-line align-bottom"></i> Geri Dön
                                </a>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $order->id }}">
                                    <i class="ri-delete-bin-line align-bottom"></i> Sifarişi Sil
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="mb-3">Sifariş Bilgileri</h6>
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td>Sifariş Nömrəsi:</td>
                                                <td class="text-end"><strong>{{ $order->order_number }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Tarix:</td>
                                                <td class="text-end">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Sifariş Tipi:</td>
                                                <td class="text-end">
                                                    @if($order->type == 'retail')
                                                        <span class="badge bg-success">Perakendə</span>
                                                    @else
                                                        <span class="badge bg-primary">Korparatif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Cəmi:</td>
                                                <td class="text-end">{{ number_format($order->total_amount, 2) }} ₼</td>
                                            </tr>
                                            <tr>
                                                <td>Ödemə Yöntəmi:</td>
                                                <td class="text-end">
                                                    @if($order->payment_method == 'cash_on_delivery')
                                                        Qapıda Ödemə
                                                    @else
                                                        {{ $order->payment_method }}
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="mb-3">Müşteri Bilgileri</h6>
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td>Ad Soyad:</td>
                                                <td class="text-end"><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></td>
                                            </tr>
                                            @if($order->type == 'corporate')
                                            <tr>
                                                <td>Şirkət Adı:</td>
                                                <td class="text-end"><strong>{{ $order->company_name }}</strong></td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>E-poçt:</td>
                                                <td class="text-end">{{ $order->email }}</td>
                                            </tr>
                                            <tr>
                                                <td>Telefon:</td>
                                                <td class="text-end">{{ $order->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>Ünvan:</td>
                                                <td class="text-end">
                                                    {{ $order->address }}
                                                    @if($order->city)
                                                        <br>{{ $order->city }}
                                                        @if($order->state), {{ $order->state }}@endif
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Məhsul</th>
                                        <th>Qiymət</th>
                                        <th>A   det</th>
                                        <th class="text-end">Cəmi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->main_image)
                                                    <img src="{{ asset($item->product->main_image) }}" alt="{{ $item->product_name }}" class="img-thumbnail me-3" style="width: 50px;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                    @if($item->color_name)
                                                        <small class="text-muted">Renk: {{ $item->color_name }}</small>
                                                    @endif
                                                    @if($item->size_name)
                                                        <small class="text-muted d-block">Beden: {{ $item->size_name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->price, 2) }} ₼</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->total, 2) }} ₼</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end">Ara Toplam:</td>
                                        <td class="text-end">{{ number_format($order->total_amount, 2) }} ₼</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">Kargo:</td>
                                        <td class="text-end">Ücretsiz</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Toplam:</strong></td>
                                        <td class="text-end"><strong>{{ number_format($order->total_amount, 2) }} ₼</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if($order->comment)
                        <div class="mt-4">
                            <h6>Sifariş Notu:</h6>
                            <p class="p-3 bg-light rounded">{{ $order->comment }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Sifariş Durumu</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('back.pages.orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="status" class="form-label">Statusu Güncelle</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Gözləmədə</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>İşlənir</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>İmtina Edildi</option>
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary w-100">Statusu Güncelle</button>
                            </div>
                        </form>

                        <hr>

                        <form action="{{ route('back.pages.orders.update-payment-status', $order->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_status" class="form-label">Ödemə Statusunu Güncelle</label>
                                <select class="form-select" id="payment_status" name="payment_status">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Gözləmədə</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Ödəndi</option>
                                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Uğursuz</option>
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-info w-100">Ödemə Statusunu Güncelle</button>
                            </div>
                        </form>
                        
                        <hr>
                        
                        <form action="{{ route('back.pages.orders.update-type', $order->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="type" class="form-label">Sifariş Tipini Güncelle</label>
                                <select class="form-select" id="type" name="type" onchange="toggleCompanyNameField()">
                                    <option value="retail" {{ $order->type == 'retail' ? 'selected' : '' }}>Perakendə</option>
                                    <option value="corporate" {{ $order->type == 'corporate' ? 'selected' : '' }}>Korparatif</option>
                                </select>
                            </div>
                            
                            <div class="mb-3" id="companyNameField" style="{{ $order->type == 'corporate' ? '' : 'display: none;' }}">
                                <label for="company_name" class="form-label">Şirkət Adı</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $order->company_name }}">
                            </div>

                            <div>
                                <button type="submit" class="btn btn-secondary w-100">Sifariş Tipini Güncelle</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Sifariş Geçmişi</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-group-wrapper">
                            <div class="timeline-item">
                                <div class="timeline-info">
                                    <span>{{ $order->created_at->format('d.m.Y H:i') }}</span>
                                </div>
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Sifariş Oluşturuldu</h6>
                                    <p class="text-muted mb-0">{{ $order->first_name }} {{ $order->last_name }} tarafından yeni sifariş verildi.</p>
                                </div>
                            </div>

                            @if($order->status != 'pending')
                            <div class="timeline-item">
                                <div class="timeline-info">
                                    <span>{{ $order->updated_at->format('d.m.Y H:i') }}</span>
                                </div>
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Durum Değiştirildi</h6>
                                    <p class="text-muted mb-0">Sifariş statusu 
                                        @if($order->status == 'processing')
                                            <span class="badge bg-info">İşlənir</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Tamamlandı</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger">İmtina Edildi</span>
                                        @endif
                                        olarak güncellendi.
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if($order->payment_status != 'pending')
                            <div class="timeline-item">
                                <div class="timeline-info">
                                    <span>{{ $order->updated_at->format('d.m.Y H:i') }}</span>
                                </div>
                                <div class="timeline-marker {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-danger' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Ödemə Statusu Deyiştirildi</h6>
                                    <p class="text-muted mb-0">Ödemə statusu 
                                        @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">Ödəndi</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger">Uğursuz</span>
                                        @endif
                                        olarak güncellendi.
                                    </p>
                                </div>
                            </div>
                            @endif
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
                Bu sifarişi silmek istediyinize əmin misiniz? Bu geri alına bilməz!
            </div>
            <div class="modal-footer">
                <form action="{{ route('back.pages.orders.destroy', $order->id) }}" method="POST" id="deleteForm">
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

@push('css')
<style>
    .timeline-group-wrapper {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        left: -30px;
        top: 0;
    }
    
    .timeline-info {
        margin-bottom: 5px;
        color: #999;
        font-size: 0.8rem;
    }
    
    .timeline-content {
        position: relative;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
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
                    $('#deleteForm').submit();
                }
            });
        });
    });
    
    // Şirket adı alanını göster/gizle
    function toggleCompanyNameField() {
        var typeSelect = document.getElementById('type');
        var companyNameField = document.getElementById('companyNameField');
        
        if (typeSelect.value === 'corporate') {
            companyNameField.style.display = 'block';
        } else {
            companyNameField.style.display = 'none';
        }
    }
</script>
@endpush 