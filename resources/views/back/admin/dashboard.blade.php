@extends('back.layouts.master')
@section('title', 'Dashboard')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- İstatistik Kartları -->
        <div class="row">
            <!-- Ürünler Kartı -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Məhsullar</p>
                                <h2 class="mt-4 ff-secondary fw-semibold">
                                    <span class="counter-value" data-target="{{ $statistics['total_products'] }}">
                                        {{ $statistics['total_products'] }}
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted">
                                    <span class="badge bg-light text-success mb-0">
                                        {{ $statistics['active_products'] }} Aktiv
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                        <i class="bx bx-shopping-bag text-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kategoriler Kartı -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Kateqoriyalar</p>
                                <h2 class="mt-4 ff-secondary fw-semibold">
                                    <span class="counter-value" data-target="{{ $statistics['total_categories'] }}">
                                        {{ $statistics['total_categories'] }}
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted">
                                    <span class="badge bg-light text-success mb-0">
                                        {{ $statistics['active_categories'] }} Aktiv
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                        <i class="bx bx-category text-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mesajlar Kartı -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Mesajlar</p>
                                <h2 class="mt-4 ff-secondary fw-semibold">
                                    <span class="counter-value" data-target="{{ $statistics['total_messages'] }}">
                                        {{ $statistics['total_messages'] }}
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted">
                                    <span class="badge bg-light text-danger mb-0">
                                        {{ $statistics['unread_messages'] }} Oxunmamış
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning rounded-circle fs-2">
                                        <i class="bx bx-envelope text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Müşteriler Kartı -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">Müştərilər</p>
                                <h2 class="mt-4 ff-secondary fw-semibold">
                                    <span class="counter-value" data-target="{{ $statistics['total_customers'] }}">
                                        {{ $statistics['total_customers'] }}
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted">
                                    <span class="badge bg-light text-success mb-0">
                                        {{ $statistics['active_customers'] }} Aktiv
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-success rounded-circle fs-2">
                                        <i class="bx bx-user-circle text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablolar -->
        <div class="row">
            <!-- Son Eklenen Ürünler -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Son Əlavə Edilən Məhsullar</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Ad</th>
                                        <th scope="col">Kateqoriya</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latest_products as $product)
                                    <tr>
                                        <td>{{ $product->name_az }}</td>
                                        <td>{{ optional($product->category)->name_az }}</td>
                                        <td>
                                            <span class="badge bg-{{ $product->status ? 'success' : 'danger' }}">
                                                {{ $product->status ? 'Aktiv' : 'Deaktiv' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Məhsul tapılmadı</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Son Mesajlar -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Son Mesajlar</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Ad</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latest_messages as $message)
                                    <tr>
                                        <td>{{ $message->name }}</td>
                                        <td>{{ $message->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $message->status ? 'success' : 'warning' }}">
                                                {{ $message->status ? 'Oxunub' : 'Oxunmayıb' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Mesaj tapılmadı</td>
                                    </tr>
                                    @endforelse
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
    // İstatistik sayaçları için animasyon
    document.addEventListener("DOMContentLoaded", function() {
        const counterElements = document.querySelectorAll('.counter-value');
        
        counterElements.forEach(element => {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 1000; // 1 saniye
            const step = target / (duration / 16); // 60 FPS
            let current = 0;
            
            const updateCounter = () => {
                current += step;
                if (current < target) {
                    element.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target;
                }
            };
            
            updateCounter();
        });
    });
</script>
@endpush