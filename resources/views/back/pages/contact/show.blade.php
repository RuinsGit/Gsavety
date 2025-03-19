@extends('back.layouts.master')

@section('title', 'İletişim Bilgisi')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4 class="mb-sm-0">İletişim Bilgisi</h4>
            <p><strong>Telefon Numarası:</strong> {{ $contact->number }}</p>
            <p><strong>E-posta:</strong> {{ $contact->mail }}</p>
            <p><strong>Adres (AZ):</strong> {{ $contact->address_az }}</p>
            <p><strong>Adres (EN):</strong> {{ $contact->address_en }}</p>
            <p><strong>Adres (RU):</strong> {{ $contact->address_ru }}</p>
            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Geri Dön</a>
        </div>
    </div>
@endsection 