@extends('back.layouts.master')

@section('title', 'Suallar')

@section('content')
<style>
    .question-title {
        font-weight: 500;
        color: #333;
        transition: all 0.3s ease;
    }

    .question-description {
        color: #666;
        font-size: 0.9rem;
        max-height: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .handle {
        cursor: move;
        color: #6c757d;
    }

    tr.ui-sortable-helper {
        background-color: #fff !important;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .swal2-popup {
        border-radius: 50px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(33, 150, 243, 0.05);
    }

    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.2rem;
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

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Suallar</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                            <li class="breadcrumb-item active">Suallar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-4">
                            <a href="{{ route('back.pages.home-questions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Yeni Sual
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;" class="text-center">#</th>
                                        <th>Başlıq</th>
                                        <th>Mətn</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 150px;">Əməliyyatlar</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable">
                                    @foreach($questions as $question)
                                        <tr data-id="{{ $question->id }}">
                                            <td class="text-center">
                                                <i class="fas fa-arrows-alt handle"></i>
                                            </td>
                                            <td>
                                                <div class="question-title">{{ $question->title_az }}</div>
                                            </td>
                                            <td>
                                                <div class="question-description">{{ $question->description_az }}</div>
                                            </td>
                                            <td>
                                                <form action="{{ route('back.pages.home-questions.toggleStatus', $question->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-{{ $question->status ? 'success' : 'danger' }}">
                                                        {{ $question->status ? 'Aktiv' : 'Deaktiv' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('back.pages.home-questions.edit', $question->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('back.pages.home-questions.destroy', $question->id) }}" 
                                                      method="POST" 
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
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
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $("#sortable").sortable({
            handle: '.handle',
            update: function(event, ui) {
                var data = $(this).sortable('toArray', { attribute: 'data-id' });
                $.ajax({
                    url: '{{ route('back.pages.home-questions.updateOrder') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        orders: data
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Sıralama uğurla yeniləndi",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            }
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            
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
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

@push('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush 