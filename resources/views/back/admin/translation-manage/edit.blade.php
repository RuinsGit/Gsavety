@extends('back.layouts.master')

@section('title', 'Tərcüməni Redaktə Et')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tərcüməni Redaktə Et</h4>

                        <form action="{{ route('back.pages.translation-manage.update', $translationManage->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="key" class="form-label">Key:</label>
                                <input type="text" name="key" id="key" class="form-control" value="{{ $translationManage->key }}" readonly required>
                            </div>
                            <div class="mb-3">
                                <label for="value_az" class="form-label">Value (AZ):</label>
                                <textarea name="value_az" id="value_az" class="form-control" required>{{ old('value_az', $translationManage->value_az) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="value_en" class="form-label">Value (EN):</label>
                                <textarea name="value_en" id="value_en" class="form-control">{{ old('value_en', $translationManage->value_en) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="value_ru" class="form-label">Value (RU):</label>
                                <textarea name="value_ru" id="value_ru" class="form-control">{{ old('value_ru', $translationManage->value_ru) }}</textarea>
                            </div>
                            <div class="mb-3 d-none">
                                <label for="group" class="form-label">Group:</label>
                                <input type="text" name="group" id="group" class="form-control" value="{{ old('group', $translationManage->group ?? 'general') }}">
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="1" {{ old('status', $translationManage->status) == 1 ? 'selected' : '' }}>Aktiv</option>
                                    <option value="0" {{ old('status', $translationManage->status) == 0 ? 'selected' : '' }}>Deaktiv</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Tərcüməni Yenilə</button>
                            <a href="{{ route('back.pages.translation-manage.index') }}" class="btn btn-secondary">Geri</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
