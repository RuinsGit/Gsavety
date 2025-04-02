
<!doctype html>
<html lang="en">


<!-- Mirrored from themesdesign.in/upcube/layouts/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 06 Oct 2023 05:29:29 GMT -->

<head>

    <meta charset="utf-8" />
    <title>@yield('title') | Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Panel" name="description" />
    <meta content="Admin Panel" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('back/assets/') }}/images/logo.png">

    <!-- jquery.vectormap css -->
    <link href="{{ asset('back/assets/') }}/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css"
        rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('back/assets/') }}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('back/assets/') }}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css"
        rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('back/assets/') }}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('back/assets/') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('back/assets/') }}/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/back-styles.css') }}" rel="stylesheet">
    
    <!-- CKEditor 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ckeditor5-build-classic@39.0.1/build/content-styles.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
    
    @stack('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body data-topbar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

    @include('back.includes.header')
    @include('back.includes.sidebar')
    <!-- Begin page -->
    <div id="layout-wrapper">

        <div class="main-content">
            @yield('content')
            @include('back.includes.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('back/assets/') }}/libs/jquery/jquery.min.js"></script>
    <script src="{{ asset('back/assets/') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('back/assets/') }}/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{ asset('back/assets/') }}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('back/assets/') }}/libs/node-waves/waves.min.js"></script>


    <!-- apexcharts -->
    <script src="{{ asset('back/assets/') }}/libs/apexcharts/apexcharts.min.js"></script>

    <!-- jquery.vectormap map -->
    <script src="{{ asset('back/assets/') }}/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js">
    </script>
    <script src="{{ asset('back/assets/') }}/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js">
    </script>

    <!-- Required datatable js -->
    <script src="{{ asset('back/assets/') }}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('back/assets/') }}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('back/assets/') }}/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('back/assets/') }}/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

    <!-- App js -->
    <script src="{{ asset('back/assets/') }}/js/app.js"></script>

    <script>
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Dashboard chartsları sadece dashboard sayfasında yükle
        if (window.location.pathname.includes('dashboard')) {
            // Dashboard chart init
            $.getScript("{{ asset('back/assets/') }}/js/pages/dashboard.init.js");
        }
    </script>
    @stack('js')
</body>


<!-- Mirrored from themesdesign.in/upcube/layouts/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 06 Oct 2023 05:30:06 GMT -->

</html>
