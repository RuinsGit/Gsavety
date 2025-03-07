@extends('back.layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Xidmətlər</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                                <li class="breadcrumb-item active">Xidmətlər</li>
                            </ol>
                        </div>

                    </div>
                    <div class="mb-3">
                        <a href="{{ route('admin.service.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Nav tabs -->
                            <ul class="nav nav-pills nav-justified" role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#az" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block">AZ</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-bs-toggle="tab" href="#en" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block">EN</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-bs-toggle="tab" href="#ru" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                        <span class="d-none d-sm-block">RU</span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3 text-muted">
                                <div class="tab-pane active" id="az" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-responsive mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>İkon</th>
                                                    <th>Başlıq (Az)</th>
                                                    <th>Yaranma tarixi</th>
                                                    <th>Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($services as $service)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div
                                                                style="display:flex;align-items:center;justify-content:center;background-color: #446981; width:100px;height:100px">
                                                                <img src="{{ asset($service->icon) }}" width="70"
                                                                    height="70" alt="">
                                                            </div>
                                                        </td>
                                                        <td>{{ $service->title_az }}</td>
                                                        <td>{{ $service->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.service.edit', ['id' => $service->id]) }}"
                                                                class="btn btn-success">
                                                                <i class="mdi mdi-account-edit"></i>
                                                            </a>
                                                            <a class="btn btn-danger"
                                                                onclick="deleteItem({{ $service->id }})">
                                                                <i class="mdi mdi-delete"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="en" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-responsive mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>İkon</th>
                                                    <th>Başlıq (En)</th>
                                                    <th>Yaranma tarixi</th>
                                                    <th>Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($services as $service)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div
                                                                style="display:flex;align-items:center;justify-content:center;background-color: #446981; width:100px;height:100px">
                                                                <img src="{{ asset($service->icon) }}" width="70"
                                                                    height="70" alt="">
                                                            </div>
                                                        </td>
                                                        <td>{{ $service->title_en }}</td>
                                                        <td>{{ $service->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.service.edit', ['id' => $service->id]) }}"
                                                                class="btn btn-success">
                                                                <i class="mdi mdi-account-edit"></i>
                                                            </a>
                                                            <a class="btn btn-danger"
                                                                onclick="deleteItem({{ $service->id }})">
                                                                <i class="mdi mdi-delete"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="ru" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-responsive mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>İkon</th>
                                                    <th>Başlıq (Ru)</th>
                                                    <th>Yaranma tarixi</th>
                                                    <th>Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($services as $service)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div
                                                                style="display:flex;align-items:center;justify-content:center;background-color: #446981; width:100px;height:100px">
                                                                <img src="{{ asset($service->icon) }}" width="70"
                                                                    height="70" alt="">
                                                            </div>
                                                        </td>
                                                        <td>{{ $service->title_ru }}</td>
                                                        <td>{{ $service->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.service.edit', ['id' => $service->id]) }}"
                                                                class="btn btn-success">
                                                                <i class="mdi mdi-account-edit"></i>
                                                            </a>
                                                            <a class="btn btn-danger"
                                                                onclick="deleteItem({{ $service->id }})">
                                                                <i class="mdi mdi-delete"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- {{ $services->withQueryString()->links('pagination::bootstrap-5') }} --}}

                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteItem(id) {
            event.preventDefault();
            let url = "{{ route('admin.service.destroy', ['id' => ':id']) }}".replace(':id', id);
            Swal.fire({
                title: 'Silmək istədiyinizdən əminsiniz mi?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Bəli!',
                confirmCancelText: 'Xeyr!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace(url);
                }
            })
        }
    </script>
@endpush
