@extends('back.layouts.master')

@section('title', '�laqə')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Əlaqə</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                        <li class="breadcrumb-item active">Əlaqə</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    @if($contacts->count() == 0)
                        <a href="{{ route('back.pages.contact.create') }}" class="btn btn-primary">Yeni Əlaqə</a>
                    @else
                        <button class="btn btn-secondary" disabled title="Maksimum 1 əlaqə məlumatı əlavə edilə bilər">Yeni Əlaqə</button>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px">ID</th>
                                <th>Nömrə</th>
                                <th>Nömrə Şəkli</th>
                                <th>E-poçt</th>
                                <th>E-poçt Şəkli</th>
                                <th>Ünvan (AZ)</th>
                                <th>Ünvan Şəkli</th>
                                <th style="width: 200px">Əməliyyatlar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                            <tr>
                                <td>{{ $contact->id }}</td>
                                <td>{{ $contact->number }}</td>
                                <td>
                                    @if($contact->number_image)
                                        <img src="{{ asset($contact->number_image) }}" alt="" style="max-height: 50px">
                                    @endif
                                </td>
                                <td>{{ $contact->mail }}</td>
                                <td>
                                    @if($contact->mail_image)
                                        <img src="{{ asset($contact->mail_image) }}" alt="" style="max-height: 50px">
                                    @endif
                                </td>
                                <td>{{ $contact->address_az }}</td>
                                <td>
                                    @if($contact->address_image)
                                        <img src="{{ asset($contact->address_image) }}" alt="" style="max-height: 50px">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('back.pages.contact.edit', $contact->id) }}" class="btn btn-sm btn-warning">Düzəliş et</a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteContact({{ $contact->id }})">Sil</button>
                                    <form id="delete-form-{{ $contact->id }}" action="{{ route('back.pages.contact.destroy', $contact->id) }}" method="POST" style="display: none;">
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
    function deleteContact(id) {
        if (confirm('Silmək istədiyinizə əminsiniz?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush 