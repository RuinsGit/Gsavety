@extends('layouts.master')

@section('title', 'Sepet İstatistikleri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Sepet İstatistikleri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('back.pages.carts.index') }}">Sepetler</a></li>
        <li class="breadcrumb-item active">İstatistikler</li>
    </ol>
    
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h2>{{ $stats['total_carts'] }}</h2>
                    <div>Toplam Sepet</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('back.pages.carts.index') }}">Detayları Görüntüle</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h2>{{ $stats['active_carts'] }}</h2>
                    <div>Aktif Sepet</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('back.pages.carts.index') }}">Detayları Görüntüle</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h2>{{ $stats['abandoned_carts'] }}</h2>
                    <div>Terk Edilmiş Sepet</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('back.pages.carts.index') }}">Detayları Görüntüle</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h2>{{ number_format($stats['avg_cart_value'], 2) }} ₺</h2>
                    <div>Ortalama Sepet Değeri</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('back.pages.carts.index') }}">Detayları Görüntüle</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    En Çok Sepete Eklenen Ürünler
                </div>
                <div class="card-body">
                    @if(count($stats['most_added_products']) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ürün ID</th>
                                        <th>Ürün Adı</th>
                                        <th>Eklenme Sayısı</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['most_added_products'] as $product)
                                        <tr>
                                            <td>{{ $product->product_id }}</td>
                                            <td>
                                                @php
                                                    $productModel = \App\Models\Product::find($product->product_id);
                                                @endphp
                                                
                                                @if($productModel)
                                                    <a href="{{ route('back.pages.products.edit', $productModel->id) }}">
                                                        {{ $productModel->name_tr }}
                                                    </a>
                                                @else
                                                    <span class="text-danger">Silinmiş Ürün</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Henüz yeterli veri bulunmamaktadır.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave me-1"></i>
                    Sepet Değeri Özeti
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Toplam Sepet Değeri:</th>
                                <td>{{ number_format($stats['total_cart_value'], 2) }} ₺</td>
                            </tr>
                            <tr>
                                <th>Ortalama Sepet Değeri:</th>
                                <td>{{ number_format($stats['avg_cart_value'], 2) }} ₺</td>
                            </tr>
                            <tr>
                                <th>Aktif Sepet Oranı:</th>
                                <td>
                                    @if($stats['total_carts'] > 0)
                                        {{ number_format(($stats['active_carts'] / $stats['total_carts']) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Terk Edilmiş Sepet Oranı:</th>
                                <td>
                                    @if($stats['total_carts'] > 0)
                                        {{ number_format(($stats['abandoned_carts'] / $stats['total_carts']) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 