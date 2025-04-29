@extends('back.layouts.master')

@section('content')
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="margin-top: 100px;">
                <h5 class="mb-0">Əlaqə Forması Detalları</h5>
                <a href="{{ route('back.pages.contact-requests.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Geri Dön
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 150px;">ID</th>
                                <td>{{ $contactRequest->id }}</td>
                            </tr>
                            <tr>
                                <th>Ad</th>
                                <td>{{ $contactRequest->first_name }}</td>
                            </tr>
                            <tr>
                                <th>Soyad</th>
                                <td>{{ $contactRequest->last_name }}</td>
                            </tr>
                            <tr>
                                <th>E-poçt</th>
                                <td>{{ $contactRequest->email }}</td>
                            </tr>
                            <tr>
                                <th>Telefon</th>
                                <td>{{ $contactRequest->phone ?? 'Qeyd edilməyib' }}</td>
                            </tr>
                            <tr>
                                <th>Sual</th>
                                <td>{{ $contactRequest->question }}</td>
                            </tr>
                            <tr>
                                <th>Tarix</th>
                                <td>{{ $contactRequest->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <form action="{{ route('back.pages.contact-requests.toggle-status', $contactRequest->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $contactRequest->status ? 'btn-success' : 'btn-danger' }}">
                                            {{ $contactRequest->status ? 'Görüldü' : 'Görülmədi' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Mesaj</h5>
                            </div>
                            <div class="card-body">
                                @if($contactRequest->comment)
                                    <p>{{ $contactRequest->comment }}</p>
                                @else
                                    <p class="text-muted">Mesaj daxil edilməyib.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <form action="{{ route('back.pages.contact-requests.destroy', $contactRequest->id) }}" method="POST" class="d-inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-trash"></i> Sil
                        </button>
                    </form>
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
            if (confirm('Bu əlaqə formasını silmək istədiyinizdən əminsiniz?')) {
                this.submit();
            }
        });
    });
</script>
@endpush 