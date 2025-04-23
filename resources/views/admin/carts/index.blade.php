@extends('back.layouts.master')

@section('title', 'Sepet Yönetimi')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Sepet Yönetimi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Sepetler</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-shopping-cart me-1"></i>
            Sepet Listesi
            <div class="float-end">
                <a href="{{ route('back.pages.carts.statistics') }}" class="btn btn-sm btn-info">
                    <i class="fas fa-chart-bar"></i> İstatistikler
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kullanıcı</th>
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
                                    @if($cart->user)
                                        <a href="{{ route('back.pages.users.show', $cart->user->id) }}">
                                            {{ $cart->user->name }} {{ $cart->user->last_name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Misafir</span>
                                    @endif
                                </td>
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