@extends('back.layouts.master')

@section('content')
    <div class="container-fluid p-4" style="margin-top: 100px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Suallar</h5>
                <a href="{{ route('back.pages.questions.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Yeni Sual
                </a>
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
                                <th>Başlıq (EN)</th>
                                <th>Başlıq (RU)</th>
                                <th>Status</th>
                                <th>İşlər</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                                <tr>
                                    <td>{{ $question->id }}</td>
                                    <td>{{ $question->title_az }}</td>
                                    <td>{{ $question->title_en }}</td>
                                    <td>{{ $question->title_ru }}</td>
                                    <td>
                                        <form action="{{ route('back.pages.questions.toggle-status', $question->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $question->status ? 'btn-success' : 'btn-danger' }}">
                                                {{ $question->status ? 'Aktiv' : 'Deaktiv' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('back.pages.questions.edit', $question->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-edit"></i> Redaktə
                                        </a>
                                        <form action="{{ route('back.pages.questions.destroy', $question->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i> Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if(count($questions) === 0)
                                <tr>
                                    <td colspan="6" class="text-center">Heç bir sual yoxdur.</td>
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
            if (confirm('Bu sualı silmək istədiyinizdən əminsiniz?')) {
                this.submit();
            }
        });
    });
</script>
@endpush 