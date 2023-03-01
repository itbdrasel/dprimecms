@extends('sourcebit::author.master')
@section('content')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-teal">
                        <div class="inner text-center">
                            <p>Today</p>
                            <h3>{{$todayArticle}}</h3>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{url('author/articles')}}" class="small-box-footer">Number of Article Post <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner text-center">
                            <p>Last 7 Day</p>
                            <h3>{{$sevenDaysArticle??0}}</h3>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{url('author/articles')}}" class="small-box-footer">Number of Article Post <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner text-center">
                            <p>Total</p>
                            <h3>{{$totalArticle??0}}</h3>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{url('author/articles')}}" class="small-box-footer">Number of Article Post <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner text-center">
                            <p>Total</p>
                            <h3>{{count($users)}}</h3>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{url('author/users')}}" class="small-box-footer">Number of Active Users <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
            <!-- /.row -->
            <div class="row">
                <section class="col-lg-6 connectedSortable ui-sortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card bg-teal">
                        <div class="card-header">
                            <h3 class="card-title text-white"><i class="ion ion-clipboard mr-1"></i>Most Popular Articles</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                <tr>
                                    <th>Article Name</th>
                                    <th class="text-right">Views</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($articles))
                                    @foreach($articles as $article)
                                        <tr>
                                            <td>{{$article->title}}</td>
                                            <td class="text-right"> {{$article->hits??''}}</td>
                                        </tr>
                                    @endforeach
                                @endif


                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-center">
                            <a href="{{url('/author/articles')}}" class="uppercase text-white">View All Article</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>



                    <!-- /.card -->
                </section>

                <section class="col-lg-6 connectedSortable ui-sortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card bg-gradient-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-white"><i class="ion ion-clipboard mr-1"></i>Category List</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Category Name</th>
                                        <th class="text-right">Article No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($categories))
                                        @foreach($categories as $category)
                                    <tr>
                                        <td>{{$category->cat_title}}</td>
                                        <td>
                                            <span class="badge badge-info float-right">{{count($category->articles??[])}}</span>
                                        </td>
                                    </tr>
                                        @endforeach
                                    @endif


                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-center">
                            <a href="{{url('/author/category')}}" class="uppercase text-white">View All Category</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>



                    <!-- /.card -->
                </section>

                <section class="col-lg-6 connectedSortable ui-sortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="ion ion-clipboard mr-1"></i>Login User List</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th class="text-right">Login Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($onlineUsers))
                                    @foreach($onlineUsers as $onlineUser)
                                        <tr>
                                            <td>{{$onlineUser->user->full_name??''}}</td>
                                            <td class="text-right">
                                                {{ date('d-m-Y h:i:s A', strtotime($onlineUser->created_at))}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif


                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-center">
                            <a href="{{url('/author/users')}}" class="uppercase">View All Users</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>



                    <!-- /.card -->
                </section>

                <section class="col-lg-6 connectedSortable ui-sortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="ion ion-clipboard mr-1"></i>Newsletter Subscribers List</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                <tr>
                                    <th>E-mail</th>
                                    <th class="text-right">Subscription Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($newsletters))
                                    @foreach($newsletters as $newsletter)
                                        <tr>
                                            <td>{{$newsletter->n_email??''}}</td>
                                            <td class="text-right">
                                                {{ date('d-m-Y h:i:s A', strtotime($newsletter->created_at))}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif


                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-center">
                            <a href="{{url('/author/newsletter')}}" class="uppercase">View All Newsletter</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>



                    <!-- /.card -->
                </section>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('css')
    <style>
        p.from_error{
            position: absolute;
            bottom: -12px;
            left: 13px;
            color: red;
            font-size: 15px;
            background-color: #fff;
            padding: 0 5px;
        }
        .custom_control{
            margin-top: 37px;
        }
        img.img_search{
            height: 130px;
            width: 130px;
            margin-top: 14px;
        }
        .btn-app{
            font-size: 15px;
            height: 130px;
            width: 155px;
            padding: 15px;
            color: #138496;
            background: linear-gradient(140deg, rgba(231,231,231,1) 0%, rgba(231,231,231,1) 50%, rgba(241,241,241,1) 50%, rgba(241,241,241,1) 100%);
        }
        .btn-app>.fa, .btn-app>.fab, .btn-app>.fad, .btn-app>.fal, .btn-app>.far, .btn-app>.fas, .btn-app>.ion, .btn-app>.svg-inline--fa{
            font-size: 50px;
            color: #138496;
        }
        th.text-center.sorting {
            width: 200px;
        }


        .label_show{
            background-color: #e7eeff;
            width: 100%;
            padding: .375rem .75rem;
        }

        .show_form{
            border-radius: 5px !important;
            background-color: #f9fbff !important;
        }


        /*Room Status admin panel design*/
        .hotel-name button{
            border: 0;
            outline: 0;
            border-radius: 0;
            padding: 0px 10px;
            margin-top: 10px;
        }
        #table td, .table th{
            padding: 5px!important;
        }
        #table th{
            font-size: 12px!important;
        }
        .room-summary-table{
            border: 1px solid #ddd;
            padding: 10px;
            /*color: #fff!important;*/
        }
        .room-summary-table hr{
            padding: 0;
            margin: 0;
        }
        .status{
            display: inline-block;
            height: 15px;
            width: 20px;
            margin-left: 5px;
        }
        .room-status p{
            margin-right: 15px;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        /*image form*/
        .img-holder{
            height: auto;
            width: 100px;
            display: flex;
        }
        .img-holder img{
            width: 100%;
            height: auto;
            border: 1px solid skyblue;
            padding: 7px;
            margin-right: 10px;
        }


        /*============== Control panel page design ==========*/
        .c-panel-boxs{
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        a.c-panel-box{
            text-decoration: none;
            height: 180px;
            width: 180px;
            padding: 20px;
            background: #1687A7;
            box-shadow: 0 2px 15px #ddd;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            font-weight: bold;
            transition: .3s;
        }
        a.c-panel-box:hover{
            color: #1E3048;
            box-shadow: none;
        }
        .c-panel-box img{
            width: 60px;
            margin: 0 auto;
        }
        .c-panel-box h3{
            font-size: 20px;
            margin-top: 10px;
            /*color: #1E3048;*/
            color: #fff;
        }


        /*======== Dashboard Index page ========= */
        .dashboard-small-box{
            background: #fff;
            box-shadow: 0 2px 15px #ddd;
            padding: 15px 20px;
        }
        .dashboard-small-box .icon span i{
            font-size: 45px;
        }
        .dashboard-small-box-bottom{
            border-top: 1px solid #ddd;
        }
        .heading p{
            color: gray;
        }
        .dashboard-small-box-bottom a{
            text-decoration: none;
            color: gray;
            display: inline-block;
            margin-top: 5px;
            font-size: 14px;
        }
        .dashboard-small-box-bottom a i{
            color: #000;
        }

        /*========== Chart 1 =============*/
        .fullscreen{
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 100%;
            /*height: 100vh;*/
            overflow: auto;
            z-index: 9999;
        }

        /*======== Transaction history ==========*/
        .media span i{
            padding: 7px;
            border-radius: 50%;
        }
        .bg-success-faded i{
            background: #eaf7ed;
        }
        .bg-info-faded i{
            background: #e3f5f8;
        }
        .bg-warning-faded i{
            background: #fff9ec;
        }
        .bg-secondary-faded i{
            background: #edeeef;
        }
    </style>
@endpush
