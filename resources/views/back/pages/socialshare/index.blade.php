@extends('back.layouts.master')

@section('title', 'Sosial Paylaşım')

@section('content')
    <style>
        .swal2-popup {
            border-radius: 50px; /* Modern görünüm için köşe yuvarlama */
        }
    </style>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "{{ session('success') }}",
                    showConfirmButton: true,
                    confirmButtonText: 'Tamam',
                    timer: 1500
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "{{ session('error') }}",
                    showConfirmButton: true,
                    confirmButtonText: 'Tamam',
                    timer: 1500
                });
            });
        </script>
    @endif

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Sosial Paylaşım</h4>

                        <div class="page-title-right">
                            <a href="{{ route('back.pages.socialshare.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Yeni əlavə et
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Sıra</th>
                                            <th>Ad</th>
                                            <th>İkon</th>
                                            <th>Link</th>
                                            <th>Status</th>
                                            <th>Əməliyyatlar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($socialshares as $socialshare)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $socialshare->name }}</td>
                                                <td>
                                                    @if($socialshare->image)
                                                        <img src="{{ asset($socialshare->image) }}" alt="" style="height: 50px; width: 50px; object-fit: cover;">
                                                    @else
                                                        <span class="text-muted">Şəkil yoxdur</span>
                                                    @endif
                                                </td>
                                                <td>{{ $socialshare->link }}</td>
                                                <td>
                                                    <form action="{{ route('back.pages.socialshare.toggleStatus', $socialshare->id) }}" method="POST" class="d-inline-block">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-{{ $socialshare->status ? 'success' : 'danger' }}">
                                                            {{ $socialshare->status ? 'Aktiv' : 'Deaktiv' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <a href="{{ route('back.pages.socialshare.edit', $socialshare->id) }}" class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $socialshare->id }}" action="{{ route('back.pages.socialshare.destroy', $socialshare->id) }}" method="POST" class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm" st onclick="deleteData({{ $socialshare->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
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
        </div>
    </div>

    <script>
        function deleteData(id) {
            Swal.fire({
                title: 'Silmək istədiyinizdən əminsiniz?',
                text: "Bu əməliyyat geri alına bilməz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Bəli, sil!',
                cancelButtonText: 'Xeyr'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endsection 