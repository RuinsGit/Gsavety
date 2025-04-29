@extends('back.layouts.master')

@section('content')
    <div class="container-fluid p-4" style="margin-top: 100px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Əlaqə Formaları</h5>
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
                                <th>Ad</th>
                                <th>Soyad</th>
                                <th>E-poçt</th>
                                <th>Telefon</th>
                                <th>Sual</th>
                                <th>Tarix</th>
                                <th>Status</th>
                                <th>İşlər</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contactRequests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->first_name }}</td>
                                    <td>{{ $request->last_name }}</td>
                                    <td>{{ $request->email }}</td>
                                    <td>{{ $request->phone ?? 'Qeyd edilməyib' }}</td>
                                    <td>{{ $request->question }}</td>
                                    <td>{{ $request->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('back.pages.contact-requests.toggle-status', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $request->status ? 'btn-success' : 'btn-danger' }}">
                                                {{ $request->status ? 'Görüldü' : 'Görülmədi' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('back.pages.contact-requests.show', $request->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> Göstər
                                        </a>
                                        <form action="{{ route('back.pages.contact-requests.destroy', $request->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i> Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if(count($contactRequests) === 0)
                                <tr>
                                    <td colspan="9" class="text-center">Hələ heç bir əlaqə forması göndərilməyib.</td>
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
            if (confirm('Bu əlaqə formasını silmək istədiyinizdən əminsiniz?')) {
                this.submit();
            }
        });
    });
</script>
@endpush 