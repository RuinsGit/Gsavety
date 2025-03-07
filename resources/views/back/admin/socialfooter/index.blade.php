@extends('back.layouts.master')
@section('title', 'Sosial Media')

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
                        <h4 class="mb-0">Sosial Media</h4>

                        <div class="page-title-right">
                            <a href="{{ route('back.pages.socialfooter.create') }}" class="btn btn-primary">
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
                                            <th>Şəkil</th>
                                            <th>Link</th>
                                            <th>Status</th>
                                            <th>Əməliyyatlar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="sortable" data-url="{{ route('back.pages.socialfooter.order') }}">
                                        @foreach($socialfooters as $socialfooter)
                                            <tr id="order-{{ $socialfooter->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <img src="{{ asset($socialfooter->image) }}" alt="" style="height: 50px; width: 50px; object-fit: cover; ">
                                                </td>
                                                <td>{{ $socialfooter->link }}</td>
                                                <td>
                                                    <form action="{{ route('back.pages.socialfooter.toggle-status', $socialfooter->id) }}" method="POST" class="d-inline-block">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-{{ $socialfooter->status ? 'success' : 'danger' }}">
                                                            {{ $socialfooter->status ? 'Aktiv' : 'Deaktiv' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <a href="{{ route('back.pages.socialfooter.edit', $socialfooter->id) }}" 
                                                       class="btn btn-primary btn-sm" style="background-color: #5bf91b; border-color: green">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $socialfooter->id }}" action="{{ route('back.pages.socialfooter.destroy', $socialfooter->id) }}" method="POST" class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteData({{ $socialfooter->id }})">
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
        $(function() {
            $('.sortable').sortable({
                handle: 'tr',
                update: function() {
                    let siralama = $('.sortable').sortable('serialize');
                    let url = $('.sortable').data('url');
                    $.post(url, {order: siralama}, function(response) {});
                }
            });
        });

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

@section('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleStatus(socialfooterId) {
            Swal.fire({
                title: 'Statusu dəyişmək istədiyinizdən əminsiniz?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Bəli',
                cancelButtonText: 'Xeyr'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch(`/admin/pages/socialfooter/toggle-status/${socialfooterId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Status dəyişdirilə bilmədi.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: error.message || 'Bir problem yaşandı.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
                }
            });
        }
    </script>
@endsection