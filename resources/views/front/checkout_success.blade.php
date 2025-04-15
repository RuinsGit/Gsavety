@extends('front.layouts.master')

@section('title', 'Sipariş Başarılı')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h2 class="mb-3">Siparişiniz Başarıyla Oluşturuldu!</h2>
                    <p class="lead mb-4">Sipariş numaranız: <strong>{{ $order->order_number }}</strong></p>
                    <p class="mb-4">Siparişinizle ilgili bir onay e-postası <strong>{{ $order->email }}</strong> adresine gönderilmiştir.</p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('front.home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i> Ana Sayfaya Dön
                        </a>
                        <a href="{{ route('front.account.orders') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-box me-2"></i> Siparişlerim
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Sipariş Detayları</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6>Sipariş Bilgileri</h6>
                            <p class="mb-1">Sipariş Numarası: {{ $order->order_number }}</p>
                            <p class="mb-1">Tarih: {{ $order->created_at->format('d.m.Y H:i') }}</p>
                            <p class="mb-1">Ödeme Yöntemi: Kapıda Ödeme</p>
                            <p class="mb-1">Durum: 
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
                            </p>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <h6>Fatura / Teslimat Adresi</h6>
                            <p class="mb-1">{{ $order->first_name }} {{ $order->last_name }}</p>
                            <p class="mb-1">{{ $order->address }}</p>
                            <p class="mb-1">{{ $order->city }}{{ $order->state ? ', ' . $order->state : '' }}</p>
                            <p class="mb-1">Tel: {{ $order->phone }}</p>
                            <p class="mb-1">E-posta: {{ $order->email }}</p>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Ürün</th>
                                    <th>Birim Fiyat</th>
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
                                                    <img src="{{ asset($item->product->main_image) }}" alt="{{ $item->product_name }}" width="50" class="img-thumbnail me-2">
                                                @endif
                                                <div>
                                                    <div>{{ $item->product_name }}</div>
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
                                    <th colspan="3" class="text-end">Ara Toplam:</th>
                                    <td class="text-end">{{ number_format($order->total_amount, 2) }} ₼</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Kargo:</th>
                                    <td class="text-end">Ücretsiz</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Toplam:</th>
                                    <td class="text-end fw-bold">{{ number_format($order->total_amount, 2) }} ₼</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($order->comment)
                        <div class="mt-4">
                            <h6>Sipariş Notu:</h6>
                            <p class="bg-light p-3 rounded">{{ $order->comment }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 