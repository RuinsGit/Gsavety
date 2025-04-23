@extends('layouts.master')

@section('title', 'Sepet Detayı')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Sepet Detayı</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('back.pages.carts.index') }}">Sepetler</a></li>
        <li class="breadcrumb-item active">Sepet #{{ $cart->id }}</li>
    </ol>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Sepet Bilgileri
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Sepet ID:</th>
                            <td>{{ $cart->id }}</td>
                        </tr>
                        <tr>
                            <th>Kullanıcı:</th>
                            <td>
                                @if($cart->user)
                                    <a href="{{ route('back.pages.users.show', $cart->user->id) }}">
                                        {{ $cart->user->name }} {{ $cart->user->last_name }}
                                    </a>
                                @else
                                    <span class="text-muted">Misafir</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Oturum ID:</th>
                            <td><small class="text-muted">{{ $cart->session_id }}</small></td>
                        </tr>
                        <tr>
                            <th>Toplam Tutar:</th>
                            <td>{{ number_format($cart->total_amount, 2) }} ₺</td>
                        </tr>
                        <tr>
                            <th>Ürün Sayısı:</th>
                            <td>{{ $cart->item_count }}</td>
                        </tr>
                        <tr>
                            <th>Durum:</th>
                            <td>
                                @if($cart->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Pasif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Oluşturulma:</th>
                            <td>{{ $cart->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Son Güncelleme:</th>
                            <td>{{ $cart->updated_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <form action="{{ route('back.pages.carts.destroy', $cart->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bu sepeti silmek istediğinize emin misiniz?')">
                            <i class="fas fa-trash"></i> Sepeti Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Sepet Ürünleri
                </div>
                <div class="card-body">
                    @if($cart->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ürün</th>
                                        <th>Varyant</th>
                                        <th>Fiyat</th>
                                        <th>Adet</th>
                                        <th>Ara Toplam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->main_image)
                                                        <img src="{{ asset($item->product->main_image) }}" alt="{{ $item->product->name_tr }}" 
                                                             class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        @if($item->product)
                                                            <a href="{{ route('back.pages.products.edit', $item->product->id) }}">
                                                                {{ $item->product->name_tr }}
                                                            </a>
                                                            <br>
                                                            <small class="text-muted">ID: {{ $item->product->id }}</small>
                                                        @else
                                                            <span class="text-danger">Silinmiş Ürün</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->color)
                                                    <span class="badge" style="background-color: {{ $item->color->color_code }}; color: {{ $this->getContrastColor($item->color->color_code) }}">
                                                        {{ $item->color->color_name_tr }}
                                                    </span>
                                                @endif
                                                
                                                @if($item->size)
                                                    <span class="badge bg-secondary">{{ $item->size->size_name_tr }}</span>
                                                @endif
                                                
                                                @if(!$item->color && !$item->size)
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->price, 2) }} ₺</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->subtotal, 2) }} ₺</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Toplam:</th>
                                        <th>{{ number_format($cart->total_amount, 2) }} ₺</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Bu sepette henüz ürün bulunmamaktadır.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php
function getContrastColor($hexcolor) {
    // Hex rengi RGB'ye dönüştür
    $r = hexdec(substr($hexcolor, 1, 2));
    $g = hexdec(substr($hexcolor, 3, 2));
    $b = hexdec(substr($hexcolor, 5, 2));
    
    // Parlaklığı hesapla (0-255)
    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    
    // Eğer parlaklık 128'den büyükse, renk açıktır - koyu yazı kullan
    return ($brightness > 128) ? '#000000' : '#FFFFFF';
}
@endphp 