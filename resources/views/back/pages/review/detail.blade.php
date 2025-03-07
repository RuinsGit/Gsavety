@extends('back.layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Dəyərləndirmə məlumatları</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                                <li class="breadcrumb-item active">Dəyərləndirmə məlumatları</li>
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
                            <h4 class="card-title">Dəyərləndirmə məlumatları</h4>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p><b>Ad, Soyad:</b>{{ $review->user->name }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><b>Məhsul :</b>{{ $review->product->title }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><b>Tarix :</b>{{ $review->created_at->format('d.m.Y') }}</p>
                                </div>
                                <div class="col-sm-12">
                                    <p><b>Qeyd :</b>{{ $review->comment }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.review.index') }}" class="btn btn-secondary">Geri</a>
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
