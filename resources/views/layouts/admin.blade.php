<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RIONA Admin Panel</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('public/admin/modules/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{asset('public/admin/modules/css/metisMenu.min.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{asset('public/admin/modules/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{{asset('public/admin/modules/css/morris.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{asset('public/admin/modules/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <!-- DataTables CSS -->
    <link href="{{asset('public/admin/modules/datatables/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous"> --}}
    <!-- DataTables Responsive CSS -->
    <link href="{{asset('public/admin/modules/datatables/css/dataTables.responsive.css')}}" rel="stylesheet">

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"
        type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <link rel="shortcut icon" type="image/png" href="{{asset('images/fav.png')}}">
    {{-- Custom stylesheet --}}
    <link href="{{asset('public/css/custom.css')}}" rel="stylesheet">
    {{-- Custom utilities css --}}
    <link href="{{asset('public/css/utilities.css')}}" rel="stylesheet">

    <script>
        var routes = {
            getSubCategories: "{{ route('getSubCategories') }}",
            getCategories: "{{ route('getCategories') }}",
            getSubChildCategories: "{{ route('getSubChildCategories') }}",
        };

    </script>

</head>

<body>

    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                @if (file_exists(public_path('images/logo-black.png')))
                <img id="Riona-logo-black" src="{{ asset('images/logo-black.png') }}" style="padding-left: 5px;height: 50px;width:auto" alt="PostQuam">
                @else
                <h2 class="pl-10">Riona</h2>
                @endif

                <img src="" alt="">
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Welcome <b>{{ Auth::user()->full_name }}</b> <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
            <!-- /.navbar-top-links -->

            @include('layouts.sidebar')
        </nav>

        <div id="page-wrapper">
            <div class="pt-10">
                @include ('layouts.partials._notifications')
                @yield('content')
            </div>
        </div>

    </div>
    {{-- scripts --}}
    <script src="{{asset('public/admin/modules/js/jquery.min.js')}}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('public/admin/modules/js/bootstrap.min.js')}}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{asset('public/admin/modules/js/metisMenu.min.js')}}"></script>

    <!-- Morris Charts JavaScript -->
    <script src="{{asset('public/admin/modules/js/raphael.min.js')}}"></script>
    {{-- <script src="{{asset('public/admin/modules/js/morris.min.js')}}"></script>
    <script src="{{asset('public/admin/modules/js/morris-data.js')}}"></script> --}}

    <!-- Custom Theme JavaScript -->
    <script src="{{asset('public/admin/modules/js/sb-admin-2.min.js')}}"></script>

    <!-- DataTables JavaScript -->
    <script src="{{asset('public/admin/modules/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('public/admin/modules/datatables/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('public/admin/modules/datatables/js/dataTables.responsive.js')}}"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {

            function dismiss_alerts() {
                window.setTimeout(function () {
                    $(".alert").fadeTo(2500, 0).slideUp(500, function () {
                        $(this).remove();
                    });
                }, 2000);
            }
            $(document).ready(function () {
                dismiss_alerts();
            });

            // Disable scroll when focused on a number input.
            $('form').on('focus', 'input[type=number]', function (e) {
                $(this).on('wheel', function (e) {
                    e.preventDefault();
                });
            });

            // Restore scroll on number inputs.
            $('form').on('blur', 'input[type=number]', function (e) {
                $(this).off('wheel');
            });

            // Disable up and down keys.
            $('form').on('keydown', 'input[type=number]', function (e) {
                if (e.which == 38 || e.which == 40)
                    e.preventDefault();
            });

        });
        $(function () {
            var date = new Date();
            date.setDate(date.getDate());
            $(".previous").datepicker({
                autoclose: true,
                todayHighlight: true,
            });
        });
        $(function () {
            var date = new Date();
            date.setDate(date.getDate());
            $(".date").datepicker({
                autoclose: true,
                todayHighlight: true,
                startDate: date
            });
        });

    </script>

    {{-- add page specific scripts --}}
    @yield('script')
</body>

</html>
