@extends('back.layouts.master')
@section('title', 'SEO Script idarəetmə')

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
                        <h4 class="mb-0">SEO Script idarəetmə</h4>
                        <div class="page-title-right">
                            <a href="{{ route('back.pages.seo_script.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Yeni Script Əlavə Et
                            </a>
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
                                        <th>ID</th>
                                        <th>Script Məzmunu</th>
                                        <th>Status</th>
                                        <th>İşlərim</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scripts as $script)
                                        <tr>
                                            <td>{{ $script->id }}</td>
                                            <td>{{ Str::limit($script->script_content, 100) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $script->status ? 'success' : 'danger' }}">
                                                    {{ $script->status ? 'Aktiv' : 'Deaktiv' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('back.pages.seo_script.edit', $script->id) }}" class="btn btn-info btn-sm" style="background-color: #5bf91b; ">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteData({{ $script->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-form-{{ $script->id }}" action="{{ route('back.pages.seo_script.destroy', $script->id) }}" method="POST" class="d-none">
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
                title: 'Silmek istediğinizden emin misiniz?',
                text: "Bu işlem geri alınamaz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'Hayır'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endsection 