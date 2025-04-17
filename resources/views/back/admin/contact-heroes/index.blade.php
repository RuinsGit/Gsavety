@extends('back.layouts.master')

@section('content')
    <div class="container-fluid p-4" style="margin-top: 100px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Əlaqə Hero</h5>
                @if($canCreate)
                <a href="{{ route('back.pages.contact-heroes.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Yeni Əlaqə Hero
                </a>
                @endif
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Başlıq (AZ)</th>
                                <th>Başlıq (EN)</th>
                                <th>Başlıq (RU)</th>
                                <th>Sıra</th>
                                <th>Status</th>
                                <th>İşlər</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contactHeroes as $hero)
                                <tr>
                                    <td>{{ $hero->id }}</td>
                                    <td>
                                        @if($hero->image)
                                            <img src="{{ asset($hero->image) }}" alt="{{ $hero->title }}" width="100">
                                        @else
                                            <span class="text-muted">Foto yoxdur</span>
                                        @endif
                                    </td>
                                    <td>{{ $hero->title_az }}</td>
                                    <td>{{ $hero->title_en }}</td>
                                    <td>{{ $hero->title_ru }}</td>
                                    <td>{{ $hero->order }}</td>
                                    <td>
                                        <form action="{{ route('back.pages.contact-heroes.toggle-status', $hero->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $hero->status ? 'btn-success' : 'btn-danger' }}">
                                                {{ $hero->status ? 'Aktiv' : 'Deaktiv' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('back.pages.contact-heroes.edit', $hero->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-edit"></i> Redaktə
                                        </a>
                                        <form action="{{ route('back.pages.contact-heroes.destroy', $hero->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i> Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if(count($contactHeroes) === 0)
                                <tr>
                                    <td colspan="8" class="text-center">Heç bir əlaqə hero yoxdur.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            if (confirm('Bu məlumatı silmək istədiyinizdən əminsiniz?')) {
                this.submit();
            }
        });
    });
</script>
@endpush 