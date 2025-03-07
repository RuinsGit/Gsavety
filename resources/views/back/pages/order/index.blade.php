@extends('back.layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Sifarişlər</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                                <li class="breadcrumb-item active">Sifarişlər</li>
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

                            <form>
                                <div class="row mb-3 align-items-end justify-content-between">
                                    <div class="col-md-1">
                                        <select name="limit" class="form-select">
                                            <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>10
                                            </option>
                                            <option value="25" {{ request('limit', 10) == 25 ? 'selected' : '' }}>25
                                            </option>
                                            <option value="50" {{ request('limit', 10) == 50 ? 'selected' : '' }}>50
                                            </option>
                                            <option value="100" {{ request('limit', 10) == 100 ? 'selected' : '' }}>100
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row align-item-end justify-content-between">
                                            <div class="col-sm-4 mb-3">
                                                <div class="input-daterange input-group" id="datepicker6"
                                                    data-date-format="dd M, yyyy" data-date-autoclose="true"
                                                    data-provide="datepicker" data-date-container='#datepicker6'>
                                                    <input type="text" class="form-control" name="start"
                                                        value="{{ request('start') }}" placeholder="Başlanğıc tarix" />
                                                    <input type="text" class="form-control" name="end"
                                                        value="{{ request('end') }}" placeholder="Son tarix" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="status" id="" class="form-select">
                                                    <option value="">Status</option>
                                                    <option value="0">Sifariş verildi</option>
                                                    <option value="1">Hazırlanır</option>
                                                    <option value="2">Karqoya verildi</option>
                                                    <option value="3">Təhvil verildi</option>
                                                    <option value="4">Ləğv olundu</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    placeholder="Axtar..." class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-primary">Axtar</button>
                                                <a href="{{ route('admin.order.index') }}"
                                                    class="btn btn-primary">Sıfırla</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>



                            <div class="table-responsive">
                                <table class="table table-responsive mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ad, Soyad</th>
                                            <th>Email</th>
                                            <th>Çatdırılma ünvanı</th>
                                            <th>Total qiymət</th>
                                            <th>Status</th>
                                            <th>Sifariş tarixi</th>
                                            <th>Əməliyyatlar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $order->name }}</td>
                                                <td>{{ $order->email }}</td>
                                                <td>{{ $order->address }}</td>
                                                <td>{{ $order->total_price }}</td>
                                                <td>
                                                    @switch($order->status)
                                                        @case(3)
                                                            <span class="badge bg-success">Təhvil verildi</span>
                                                        @break

                                                        @case(4)
                                                            <span class="badge bg-danger">Ləğv edildi</span>
                                                        @break

                                                        @default
                                                            <select onchange="change_status(this)" data-id="{{ $order->id }}"
                                                                class="form-select change-status">
                                                                <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>
                                                                    Sifariş verildi</option>
                                                                <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>
                                                                    Hazırlanır</option>
                                                                <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>
                                                                    Karqoya verildi</option>
                                                                <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>
                                                                    Sifariş verildi</option>
                                                                <option value="4" {{ $order->status == 4 ? 'selected' : '' }}>
                                                                    Ləğv edildi</option>
                                                            </select>
                                                        @break
                                                    @endswitch
                                                </td>
                                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.order.detail', ['id' => $order->id]) }}"
                                                        class="btn btn-warning">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    <a class="btn btn-danger" onclick="deleteItem({{ $order->id }})">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}

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
            let url = "{{ route('admin.order.destroy', ['id' => ':id']) }}".replace(':id', id);
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

    <script>
        function change_status(elem) {
            let status = elem.value;
            let id = elem.getAttribute('data-id');
            let url = `/admin/order/${id}/change-status`;
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        '_token': "{{ csrf_token() }}",
                        'status': status
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status == 'success') {
                        setInterval(() => window.location.reload(), 1500);
                    }
                })
        }
    </script>
@endpush
