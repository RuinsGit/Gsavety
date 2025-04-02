@extends('back.layouts.master')
@section('title', 'Yeni Mərkəz Haqqında')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Yeni Mərkəz Haqqında</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('back.pages.index') }}">Ana Səhifə</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('back.pages.about-center-cart.index') }}">Mərkəz Haqqında</a></li>
                        <li class="breadcrumb-item active">Yeni</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="createForm" method="POST" action="{{ route('back.pages.about-center-cart.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="image" class="form-label">Şəkil</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <div class="invalid-feedback" id="image-error"></div>
                        </div>

                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#az" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Azərbaycan</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#en" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">English</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#ru" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Русский</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="az" role="tabpanel">
                                <div class="mb-3">
                                    <label for="title_az" class="form-label">Başlıq (AZ)</label>
                                    <input type="text" class="form-control" id="title_az" name="title_az" value="{{ old('title_az') }}">
                                    <div class="invalid-feedback" id="title_az-error"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="description_az" class="form-label">Təsvir (AZ)</label>
                                    <div id="editor_az"></div>
                                    <textarea class="form-control d-none" id="description_az" name="description_az" rows="5">{{ old('description_az') }}</textarea>
                                    <div class="invalid-feedback" id="description_az-error"></div>
                                </div>
                            </div>

                            <div class="tab-pane" id="en" role="tabpanel">
                                <div class="mb-3">
                                    <label for="title_en" class="form-label">Title (EN)</label>
                                    <input type="text" class="form-control" id="title_en" name="title_en" value="{{ old('title_en') }}">
                                    <div class="invalid-feedback" id="title_en-error"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="description_en" class="form-label">Description (EN)</label>
                                    <div id="editor_en"></div>
                                    <textarea class="form-control d-none" id="description_en" name="description_en" rows="5">{{ old('description_en') }}</textarea>
                                    <div class="invalid-feedback" id="description_en-error"></div>
                                </div>
                            </div>

                            <div class="tab-pane" id="ru" role="tabpanel">
                                <div class="mb-3">
                                    <label for="title_ru" class="form-label">Заголовок (RU)</label>
                                    <input type="text" class="form-control" id="title_ru" name="title_ru" value="{{ old('title_ru') }}">
                                    <div class="invalid-feedback" id="title_ru-error"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="description_ru" class="form-label">Описание (RU)</label>
                                    <div id="editor_ru"></div>
                                    <textarea class="form-control d-none" id="description_ru" name="description_ru" rows="5">{{ old('description_ru') }}</textarea>
                                    <div class="invalid-feedback" id="description_ru-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Yadda Saxla</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<!-- Sadece bu sayfa için gerekli ek CSS -->
<style>
    .ck-editor__editable {
        min-height: 300px;
    }
    .ck-editor__editable_inline {
        border: 1px solid var(--ck-color-base-border);
        padding: 0 var(--ck-spacing-standard);
    }
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CKEditor'leri başlat
    let editors = {};
    
    ClassicEditor
        .create(document.querySelector('#editor_az'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
        })
        .then(editor => {
            editors.az = editor;
            
            // Eğer textarea'da veri varsa CKEditor'e aktar
            const textareaContent = document.querySelector('#description_az').value;
            if (textareaContent) {
                editor.setData(textareaContent);
            }
        })
        .catch(error => {
            console.error(error);
        });
        
    ClassicEditor
        .create(document.querySelector('#editor_en'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
        })
        .then(editor => {
            editors.en = editor;
            
            // Eğer textarea'da veri varsa CKEditor'e aktar
            const textareaContent = document.querySelector('#description_en').value;
            if (textareaContent) {
                editor.setData(textareaContent);
            }
        })
        .catch(error => {
            console.error(error);
        });
        
    ClassicEditor
        .create(document.querySelector('#editor_ru'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
        })
        .then(editor => {
            editors.ru = editor;
            
            // Eğer textarea'da veri varsa CKEditor'e aktar
            const textareaContent = document.querySelector('#description_ru').value;
            if (textareaContent) {
                editor.setData(textareaContent);
            }
        })
        .catch(error => {
            console.error(error);
        });

    // Form gönderimi
    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        
        // CKEditor içeriklerini textarea'lara aktar
        if (editors.az) {
            document.querySelector('#description_az').value = editors.az.getData();
        }
        if (editors.en) {
            document.querySelector('#description_en').value = editors.en.getData();
        }
        if (editors.ru) {
            document.querySelector('#description_ru').value = editors.ru.getData();
        }
        
        // Reset error messages
        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');
        
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Uğurlu!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('back.pages.about-center-cart.index') }}";
                        }
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key + '-error').text(value[0]);
                    });
                } else {
                    var errorMessage = 'Xəta baş verdi!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        title: 'Xəta!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    });
});
</script>
@endpush
