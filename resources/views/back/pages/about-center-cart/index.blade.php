@extends('back.layouts.master')

@section('title', 'Mərkəz Haqqında')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Mərkəz Haqqında</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                        <li class="breadcrumb-item active">Mərkəz Haqqında</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                
                <div class="card-header">
                    @if(count($carts) < 1)
                    <a href="{{ route('back.pages.about-center-cart.create') }}" class="btn btn-primary">Yeni Məlumat</a>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px">ID</th>
                                <th>Başlıq (AZ)</th>
                                <th>Şəkil</th>
                                <th style="width: 200px">Əməliyyatlar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart)
                            <tr>
                                <td>{{ $cart->id }}</td>
                                <td>{{ $cart->title_az }}</td>
                                <td>
                                    @if($cart->image)
                                        <img src="{{ asset($cart->image) }}" alt="" style="max-height: 50px">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('back.pages.about-center-cart.edit', $cart->id) }}" class="btn btn-sm btn-warning">Düzəliş et</a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteCart({{ $cart->id }})">Sil</button>
                                    <form id="delete-form-{{ $cart->id }}" action="{{ route('back.pages.about-center-cart.destroy', $cart->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function deleteCart(id) {
        Swal.fire({
            title: 'Əminsiniz?',
            text: "Bu məlumat silinəcək!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Bəli, sil!',
            cancelButtonText: 'Xeyr, ləğv et!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            title: 'Uğurlu!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Tamam'
        });
    @endif
</script>
@endpush 