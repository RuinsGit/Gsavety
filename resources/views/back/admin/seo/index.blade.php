@extends('back.layouts.master')
@section('title', 'SEO Siyahısı')

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
                        <h4 class="mb-0">SEO Siyahısı</h4>
                        <div class="page-title-right">
                            @if($seos->count() < 11)
                                <a href="{{ route('back.pages.seo.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Yeni
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Key</th>
                                        <th>Meta Title (AZ)</th>
                                        <th>Meta Description (AZ)</th>
                                        <th>Status</th>
                                        <th>Əməliyyatlar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seos as $seo)
                                        <tr>
                                            <td>{{ $seo->key }}</td>
                                            <td>{{ $seo->meta_title_az }}</td>
                                            <td>{{ Str::limit($seo->meta_description_az, 50) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $seo->status ? 'success' : 'danger' }}">
                                                    {{ $seo->status ? 'Aktiv' : 'Deaktiv' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('back.pages.seo.edit', $seo->id) }}" class="btn btn-info btn-sm" style="background-color: #5bf91b; ">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- <button type="button" class="btn btn-danger btn-sm" onclick="deleteData({{ $seo->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button> -->
                                                <form id="delete-form-{{ $seo->id }}" action="{{ route('back.pages.seo.destroy', $seo->id) }}" method="POST" class="d-none">
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