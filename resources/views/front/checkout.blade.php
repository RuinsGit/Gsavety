@extends('front.layouts.master')

@section('title', 'Sipariş')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="mb-4">Sipariş</h2>
                    
                    <form action="{{ route('front.checkout.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">Ad <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Soyad <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Adres</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="city" class="form-label">Şehir</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="state" class="form-label">Bölge</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label">Yorum</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Gizli alan olarak default değerleri ekliyoruz -->
                        <input type="hidden" name="payment_method" value="cash_on_delivery">
                        <input type="hidden" name="country" value="Azerbaijan">
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Sipariş Ver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Sepetinizde</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                @if($item['product']->main_image)
                                    <img src="{{ asset($item['product']->main_image) }}" alt="{{ $item['product']->name_az }}" width="60" class="img-thumbnail">
                                @else
                                    <div class="bg-light text-center" style="width: 60px; height: 60px; line-height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $item['product']->name_az }}</h6>
                                <div class="small text-muted">
                                    @if($item['color'])
                                        <div>Renk: {{ $item['color']->color_name_az }}</div>
                                    @endif
                                    @if($item['size'])
                                        <div>Beden: {{ $item['size']->size_name_az }}</div>
                                    @endif
                                    <div>Adet: {{ $item['quantity'] }} x {{ number_format($item['price'], 2) }} ₼</div>
                                </div>
                                <div class="mt-1 fw-bold">
                                    {{ number_format($item['total'], 2) }} ₼
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Ara Toplam:</span>
                        <span>{{ number_format($total, 2) }} ₼</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Kargo:</span>
                        <span>Ücretsiz</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center fw-bold">
                        <span>Toplam:</span>
                        <span>{{ number_format($total, 2) }} ₼</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 