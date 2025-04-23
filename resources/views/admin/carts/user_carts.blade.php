@extends('layouts.back')

@section('title', 'Kullanıcı Sepetleri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Kullanıcı Sepetleri: {{ $user->name }} {{ $user->last_name }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('back.pages.carts.index') }}">Sepetler</a></li>
        <li class="breadcrumb-item active">{{ $user->name }} {{ $user->last_name }}</li>
    </ol>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Kullanıcı Bilgileri
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>Ad Soyad:</th>
                            <td>{{ $user->name }} {{ $user->last_name }}</td>
                        </tr>
                        <tr>
                            <th>E-posta:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Telefon:</th>
                            <td>{{ $user->phone ?? 'Belirtilmemiş' }}</td>
                        </tr>
                        <tr>
                            <th>Kayıt Tarihi:</th>
                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('back.pages.users.show', $user->id) }}" class="btn btn-primary">
                        <i class="fas fa-user"></i> Kullanıcı Detayına Git
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Sepet Özeti
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Toplam Sepet Sayısı:</th>
                            <td>{{ $carts->total() }}</td>
                        </tr>
                        <tr>
                            <th>Aktif Sepet Sayısı:</th>
                            <td>{{ $carts->where('is_active', true)->count() }}</td>
                        </tr>
                        <tr>
                            <th>Toplam Sepet Değeri:</th>
                            <td>{{ number_format($carts->sum('total_amount'), 2) }} ₺</td>
                        </tr>
                        <tr>
                            <th>Ortalama Sepet Değeri:</th>
                            <td>
                                {{ number_format($carts->where('total_amount', '>', 0)->avg('total_amount') ?? 0, 2) }} ₺
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-shopping-cart me-1"></i>
            {{ $user->name }} {{ $user->last_name }} Kişisine Ait Sepetler
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($carts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Oturum ID</th>
                                <th>Ürün Sayısı</th>
                                <th>Toplam Tutar</th>
                                <th>Durum</th>
                                <th>Son Güncelleme</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart)
                                <tr>
                                    <td>{{ $cart->id }}</td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($cart->session_id, 10) }}</small>
                                    </td>
                                    <td>{{ $cart->item_count }}</td>
                                    <td>{{ number_format($cart->total_amount, 2) }} ₺</td>
                                    <td>
                                        @if($cart->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Pasif</span>
                                        @endif
                                    </td>
                                    <td>{{ $cart->updated_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('back.pages.carts.show', $cart->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <form action="{{ route('back.pages.carts.destroy', $cart->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu sepeti silmek istediğinize emin misiniz?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $carts->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    {{ $user->name }} {{ $user->last_name }} adlı kullanıcıya ait sepet bulunmamaktadır.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            paging: false,
            searching: true,
            ordering: true,
            info: false,
        });
    });
</script>
@endsection 