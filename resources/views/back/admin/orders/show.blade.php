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
                    <h4 class="mb-sm-0">Sipariş Detayı</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana sayfa</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.orders.index') }}">Siparişler</a></li>
                            <li class="breadcrumb-item active">Sipariş Detayı</li>
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
                            <h5 class="card-title mb-0 flex-grow-1">Sipariş #{{ $order->order_number }}</h5>
                            <div class="flex-shrink-0">
                                <a href="{{ route('back.pages.orders.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="ri-arrow-left-line align-bottom"></i> Geri Dön
                                </a>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $order->id }}">
                                    <i class="ri-delete-bin-line align-bottom"></i> Siparişi Sil
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="mb-3">Sipariş Bilgileri</h6>
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td>Sipariş Numarası:</td>
                                                <td class="text-end"><strong>{{ $order->order_number }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Tarih:</td>
                                                <td class="text-end">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Toplam Tutar:</td>
                                                <td class="text-end">{{ number_format($order->total_amount, 2) }} ₼</td>
                                            </tr>
                                            <tr>
                                                <td>Ödeme Yöntemi:</td>
                                                <td class="text-end">
                                                    @if($order->payment_method == 'cash_on_delivery')
                                                        Kapıda Ödeme
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
                                            <tr>
                                                <td>E-posta:</td>
                                                <td class="text-end">{{ $order->email }}</td>
                                            </tr>
                                            <tr>
                                                <td>Telefon:</td>
                                                <td class="text-end">{{ $order->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>Adres:</td>
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
                                        <th>Ürün</th>
                                        <th>Fiyat</th>
                                        <th>Adet</th>
                                        <th class="text-end">Toplam</th>
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
                            <h6>Sipariş Notu:</h6>
                            <p class="p-3 bg-light rounded">{{ $order->comment }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Sipariş Durumu</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('back.pages.orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="status" class="form-label">Durumu Güncelle</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Beklemede</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>İşleniyor</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary w-100">Durumu Güncelle</button>
                            </div>
                        </form>

                        <hr>

                        <form action="{{ route('back.pages.orders.update-payment-status', $order->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_status" class="form-label">Ödeme Durumunu Güncelle</label>
                                <select class="form-select" id="payment_status" name="payment_status">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Beklemede</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Ödendi</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Başarısız</option>
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-info w-100">Ödeme Durumunu Güncelle</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Sipariş Geçmişi</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-group-wrapper">
                            <div class="timeline-item">
                                <div class="timeline-info">
                                    <span>{{ $order->created_at->format('d.m.Y H:i') }}</span>
                                </div>
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Sipariş Oluşturuldu</h6>
                                    <p class="text-muted mb-0">{{ $order->first_name }} {{ $order->last_name }} tarafından yeni sipariş verildi.</p>
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
                                    <p class="text-muted mb-0">Sipariş durumu 
                                        @if($order->status == 'processing')
                                            <span class="badge bg-info">İşleniyor</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Tamamlandı</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger">İptal Edildi</span>
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
                                    <h6 class="mb-0">Ödeme Durumu Değiştirildi</h6>
                                    <p class="text-muted mb-0">Ödeme durumu 
                                        @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">Ödendi</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger">Başarısız</span>
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
                <h5 class="modal-title">Onay</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bu siparişi silmek istediğinize emin misiniz? Bu işlem geri alınamaz!
            </div>
            <div class="modal-footer">
                <form action="{{ route('back.pages.orders.destroy', $order->id) }}" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
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
</script>
@endpush 