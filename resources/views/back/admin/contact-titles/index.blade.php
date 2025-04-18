@extends('back.layouts.master')

@section('content')
    <div class="container-fluid p-4" style="margin-top: 100px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Əlaqə Başlıqları</h5>
                @if($canCreate)
                <a href="{{ route('back.pages.contact-titles.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Yeni Əlaqə Başlığı
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
                                <th>Başlıq (AZ)</th>
                                <th>Xüsusi Başlıq (AZ)</th>
                                <th>Status</th>
                                <th>İşlər</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contactTitles as $title)
                                <tr>
                                    <td>{{ $title->id }}</td>
                                    <td>{{ $title->title_az }}</td>
                                    <td>{{ $title->special_title_az }}</td>
                                    <td>
                                        <form action="{{ route('back.pages.contact-titles.toggle-status', $title->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $title->status ? 'btn-success' : 'btn-danger' }}">
                                                {{ $title->status ? 'Aktiv' : 'Deaktiv' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('back.pages.contact-titles.edit', $title->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-edit"></i> Redaktə
                                        </a>
                                        <form action="{{ route('back.pages.contact-titles.destroy', $title->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i> Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if(count($contactTitles) === 0)
                                <tr>
                                    <td colspan="5" class="text-center">Heç bir əlaqə başlığı yoxdur.</td>
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