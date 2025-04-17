@extends('back.layouts.master')

@section('content')
    <div class="container-fluid p-4" style="margin-top: 100px;">
        <div class="card">
        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="card-title">Servis Hero</h4>
                            @if($canCreate)
                                <a href="{{ route('back.pages.service-heroes.create') }}" class="btn btn-primary waves-effect waves-light">
                                    <i class="ri-add-line align-middle me-1"></i> Yeni
                                </a>
                            @endif
                        </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Başliq (AZ)</th>
                                <th>Başliq (EN)</th>
                                <th>Başliq (RU)</th>
                                <th>Sıra</th>
                                <th>Status</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($serviceHeroes as $hero)
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
                                        <form action="{{ route('back.pages.service-heroes.toggle-status', $hero->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $hero->status ? 'btn-success' : 'btn-danger' }}">
                                                {{ $hero->status ? 'Aktiv' : 'Deaktiv' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('back.pages.service-heroes.edit', $hero->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-edit"></i> Yenilə
                                        </a>
                                        <form action="{{ route('back.pages.service-heroes.destroy', $hero->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i> sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if(count($serviceHeroes) === 0)
                                <tr>
                                    <td colspan="8" class="text-center">Heç bir servis hero yoxdur.</td>
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
            if (confirm('Bu kaydı silmek istediğinizden emin misiniz?')) {
                this.submit();
            }
        });
    });
</script>
@endpush 