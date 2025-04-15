@extends('back.layouts.master')

@section('title', 'Kullanıcılar')

@section('content')
<style>
    .swal2-popup {
        border-radius: 50px;
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
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Kullanıcılar</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Sayfa</a></li>
                            <li class="breadcrumb-item active">Kullanıcılar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h4 class="card-title">Kullanıcı Listesi</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('back.pages.users.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus me-1"></i> Yeni Kullanıcı
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>İsim</th>
                                        <th>E-posta</th>
                                        <th>Rol</th>
                                        <th>Durum</th>
                                        <th>Kayıt Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role == 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @else
                                                <span class="badge bg-info">Kullanıcı</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch form-switch-success" style="margin-bottom: 0 !important">
                                                <input class="form-check-input status-switch" type="checkbox" role="switch" id="statusSwitch_{{ $user->id }}" data-id="{{ $user->id }}" {{ $user->status ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('back.pages.users.edit', $user->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Düzenle">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $user->id }}">
                                                    <i class="ri-delete-bin-fill"></i>
                                                </button>
                                            </div>
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

<!-- Silme Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Onay</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bu kullanıcıyı silmek istediğinize emin misiniz?
            </div>
            <div class="modal-footer">
                <form action="" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<!-- Required datatable js -->
<script src="{{ asset('back/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('back/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<!-- Responsive datatable -->
<script src="{{ asset('back/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('back/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // DataTable
        $('#datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json',
            },
            "columnDefs": [
                { "orderable": false, "targets": [4, 6] }
            ]
        });

        // Tooltip
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Silme işlemi
        $('.delete-btn').on('click', function() {
            var id = $(this).data('id');
            
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu kullanıcıyı silmek istediğinize emin misiniz?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('back.pages.users.destroy', ':id') }}";
                    url = url.replace(':id', id);
                    
                    var form = $('#deleteForm');
                    form.attr('action', url);
                    form.submit();
                }
            });
        });

        // Durum değiştirme
        $('.status-switch').on('change', function() {
            var id = $(this).data('id');
            var url = "{{ route('back.pages.users.toggle-status', ':id') }}";
            url = url.replace(':id', id);
            
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Kullanıcı durumu başarıyla değiştirildi",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: response.message || "Bir hata oluştu",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        // Durum değişikliğini geri al
                        $('#statusSwitch_' + id).prop('checked', !$('#statusSwitch_' + id).prop('checked'));
                    }
                },
                error: function(xhr) {
                    var message = xhr.responseJSON && xhr.responseJSON.message 
                        ? xhr.responseJSON.message 
                        : "Bir hata oluştu";
                    
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Durum değişikliğini geri al
                    $('#statusSwitch_' + id).prop('checked', !$('#statusSwitch_' + id).prop('checked'));
                }
            });
        });
    });
</script>
@endpush 