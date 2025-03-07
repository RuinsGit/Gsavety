@extends('back.layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $order->code }} №-li sifariş məlumatları</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                                <li class="breadcrumb-item active">{{ $order->code }} №-li sifariş məlumatları</li>
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
                            <h4 class="card-title">{{ $order->code }} №-li sifariş məlumatları</h4>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p><b>Sifariş № :</b>{{ $order->code }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><b>Ad, Soyad:</b>{{ $order->name }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><b>Email :</b>{{ $order->email }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><b>Telefon nömrəsi :</b>{{ $order->phone }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><b>Çatdırılma ünvanı :</b>{{ $order->address }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><b>Ümumi məbləğ :</b>{{ $order->total_price }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><b>Çatdırılma növü
                                            :</b>{{ $order->delivery_type == 'delivery_address' ? 'Ünvana çatdırılma' : 'Mağazadan al' }}
                                    </p>
                                </div>
                                <div class="col-sm-4">
                                    <p><b>Ödəmə növü
                                            :</b>{{ $order->payment_type == 'debet' ? 'Debet kart' : 'Bank hesabı' }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><b>Status :</b>
                                        @switch($order->status)
                                            @case(0)
                                                <span class="badge bg-warning">Sifariş verildi</span>
                                            @break

                                            @case(1)
                                                <span class="badge bg-primary">Hazırlanır</span>
                                            @break

                                            @case(2)
                                                <span class="badge bg-primary">Karqoya verildi</span>
                                            @break

                                            @case(3)
                                                <span class="badge bg-success">Təhvil verildi</span>
                                            @break

                                            @case(4)
                                                <span class="badge bg-danger">Ləğv edildi</span>
                                            @break
                                        @endswitch
                                    </p>
                                </div>
                                <div class="col-sm-12">
                                    <p><b>Qeyd :</b>{{ $order->info }}</p>
                                </div>
                            </div>
                            <div class="card-header my-2">
                                <h2 class="text-center">
                                    Məhsullar
                                    <i class="mdi mdi-information text-primary"></i>
                                </h2>
                            </div>
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Məhsul adı</th>
                                        <th>Məhsulun qiyməti</th>
                                        <th>Say</th>
                                        <th>Ümumi dəyər</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->order_products as $order_product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $order_product->product_name }}</td>
                                            <td>{{ $order_product->product_price }}</td>
                                            <td>{{ $order_product->count }}</td>
                                            <td>{{ $order_product->product_price * $order_product->count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <a href="{{ route('admin.order.index') }}" class="btn btn-secondary">Geri</a>
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
