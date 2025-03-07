@extends('back.layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Şəhərlər</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.city.index') }}">Şəhərlər</a>
                                </li>
                                <li class="breadcrumb-item active">Redaktə et</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Şəhər redaktə et</h4>
                            <form class="needs-validation" method="POST"
                                action="{{ route('admin.city.update', ['id' => $city->id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="tab-pane active" id="az">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="validationCustom03" class="form-label">Ad (Ru)</label>
                                                <input type="text" name="name_az" value="{{ $city->name_az }}"
                                                    class="form-control">
                                                @error('name_az')
                                                    <div class="invalid-feedback" style="display: block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">Təsdiqlə</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end card -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
