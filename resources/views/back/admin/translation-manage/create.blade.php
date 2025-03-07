@extends('back.layouts.master')

@section('title', 'Yeni Tərcümə')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add New Translation</h4>

                        <form action="{{ route('back.pages.translation-manage.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="key" class="form-label">Key:</label>
                                <input type="text" name="key" id="key" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="value_az" class="form-label">Value (AZ):</label>
                                <textarea name="value_az" id="value_az" class="form-control" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="value_en" class="form-label">Value (EN):</label>
                                <textarea name="value_en" id="value_en" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="value_ru" class="form-label">Value (RU):</label>
                                <textarea name="value_ru" id="value_ru" class="form-control"></textarea>
                            </div>
                            <div class="mb-3 d-none">
                                <label for="group" class="form-label">Group:</label>
                                <input type="text" name="group" id="group" class="form-control" value="general">
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="1">Aktiv</option>
                                    <option value="0">Deaktiv</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Tərcüməni Yarat</button>
                            <a href="{{ route('back.pages.translation-manage.index') }}" class="btn btn-secondary">Geri</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 