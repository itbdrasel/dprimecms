
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" data-enable-remember="true" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>

        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <!-- Notifications Dropdown Menu -->
            <li class="nav-item">
                <a  id="clearData" style="cursor: pointer" class="nav-link "><i class="fas fa-trash-alt"></i> &nbsp;Clear Cache</a>
            </li>
            <li class="nav-item">
                 <a href="{{url('/')}}" target="_blank" class="nav-link"><i class="nav-icon fas fa-globe"></i> &nbsp;Website</a>
            </li>
            <style>
                .dropdown-menu-lg{
                    min-width: auto;
                }
            </style>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="nav-icon far fa-user"></i>&nbsp; {{dAuth()->getUser()->full_name}} ({{dAuth()->getUser()->roles->first()->name}})
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <div class="dropdown-divider"></div>
                    <a href="{{url('author/profile/'. dAuth()->getUser()->id)}}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Your Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{url('author/logout')}}" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </li>


            <li class="nav-item" style=" ">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            
        </ul>
    </nav>
    <!-- /.navbar -->


@push('js')
        <script>
            $('#clearData').on('click', function () {
                $.ajax({
                    type:'get',
                    url:'{{url('all/clear')}}',
                    success:function(data) {
                        toastr.success('<a style="" href="{{url()->current()}}">  Reload This Page</a>', data)
                    }
                });
            });
        </script>

@endpush
