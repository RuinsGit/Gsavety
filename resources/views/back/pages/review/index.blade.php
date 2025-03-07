@extends('back.layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Məhsul qiymətləndirmələri</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                                <li class="breadcrumb-item active">Məhsul qiymətləndirmələri</li>
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
                                    <div class="col-md-11">
                                        <div class="row align-item-end justify-content-between">
                                            <div class="col-sm-4">
                                                <div class="input-daterange input-group" id="datepicker6"
                                                    data-date-format="dd M, yyyy" data-date-autoclose="true"
                                                    data-provide="datepicker" data-date-container='#datepicker6'>
                                                    <input type="text" class="form-control" name="start"
                                                        value="{{ request('start') }}" placeholder="Başlanğıc tarix" />
                                                    <input type="text" class="form-control" name="end"
                                                        value="{{ request('end') }}" placeholder="Son tarix" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="product_id" id="" class="form-select">
                                                    <option value="">Məhsul</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                            {{ $product->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    placeholder="Axtar..." class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-primary">Axtar</button>
                                                <a href="{{ route('admin.review.index') }}"
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
                                            <th>Məhsul</th>
                                            <th>Dəyər</th>
                                            <th>Tarix</th>
                                            <th>Əməliyyatlar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reviews as $review)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $review->user->name }}</td>
                                                <td>{{ $review->product->title }}</td>
                                                <td>
                                                    <div class="rating-star">
                                                        <span style="cursor: default;">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($review->rank >= $i)
                                                                    <div class="rating-symbol"
                                                                        style="display: inline-block; position: relative;">
                                                                        <div class="rating-symbol-background mdi mdi-star-outline text-muted"
                                                                            style="visibility: hidden;"></div>
                                                                        <div class="rating-symbol-foreground"
                                                                            style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                                                            <span class="mdi mdi-star text-primary"></span>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="rating-symbol"
                                                                        style="display: inline-block; position: relative;">
                                                                        <div class="rating-symbol-background mdi mdi-star-outline text-muted"
                                                                            style="visibility: visible;"></div>
                                                                        <div class="rating-symbol-foreground"
                                                                            style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: 0px;">
                                                                            <span></span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endfor
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.review.detail', ['id' => $review->id]) }}"
                                                        class="btn btn-warning">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    <a class="btn btn-danger" onclick="deleteItem({{ $review->id }})">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $reviews->withQueryString()->links('pagination::bootstrap-5') }}

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
            let url = "{{ route('admin.review.destroy', ['id' => ':id']) }}".replace(':id', id);
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
            let url = `/admin/review/${id}/change-status`;
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
