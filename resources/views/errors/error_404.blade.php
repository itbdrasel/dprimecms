<!DOCTYPE html>
<html lang="en">
<head>
    @php
    //$title = '04 Page Not Found';
    //$menus = \App\Models\Menu::get()
    @endphp
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>404 Custom Error Page Example</title>

    <link href="{{asset('images/f.ico')}}" rel="shortcut icon"/>

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="{{url('frontend/css/bootstrap4.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{url('frontend/css/animate4.min.css') }}"/>

    <link rel="stylesheet" href="{{url('frontend/css/style.css') }}" type="text/css"/>
    
    <style type="text/css">

        .last{ color:#fff; font-size:14px; margin-top:35px;}
        .last a{ font-size:17px; margin-left:10px; margin-right:10px;}
        .last a i{ color:#999;}
        .last .or{ padding:5px 6px; border:1px solid #fff; border-radius:50%}
        .full-width{ background-color: #6b8baf;}

        .content-inner {
            position: relative;
            min-height: 500px !important;
            z-index: 100;
            padding-bottom: 60px;
        }
        .content {
            margin-top: 80px;
        }
        .main{}
        .text404 {
            width: auto;
            margin: 0px auto;
            margin-top: 80px;
            cursor: default;
            animation: fadeIn 0.6s ease-in-out 2s;
            animation-fill-mode: forwards;
        }
        .main .e{ color:#fff !important; display:block; margin-bottom:20px; font-size:50px}
        .text404-mid {
            font-family: 'Days One', cursive;
            text-transform: uppercase;
            font-size: 30px;
            letter-spacing: 3px;
            line-height: 40px;
            color:#fff;
            width: auto;
            color: rgba(255,255,255,0.5);
        }
        .desc{ color:#fff;}
        .desc h4 {
            color: #ffffff;
            font-family: 'Montserrat Alternates', sans-serif;
            font-size: 22px;
            line-height: 34px;
            padding-bottom: 10px;
            padding-top: 30px;
            max-width: 600px;
        }
        .search {
            position: relative;
            height: 40px;
            margin: 20px 0px;
            max-width: 450px;
        }
        #search input[type="text"] {
            background-color: rgba(255,255,255,0.8);
            border: 0 none;
            border-radius: 20px;
            color: #000000;
            font-family: inherit;
            font-size: 16px;
            padding: 9px 45px 9px 15px;
            transition: all 0.7s ease 0s;
            width: 100%;
        }

        .search-submit {
            background: #fff;
            -ms-filter: "progid: dximagetransform.microsoft.alpha(opacity=0)";
            filter: alpha(opacity=0);
            opacity: 0;
            color: transparent;
            border: none;
            outline: none;
        }
        a.btn.btn-default{
            background: #fff;
        }

        .right{ text-align:center; margin-top:50px}

        .right img{ padding:20px 30px; border:1px dotted #fff; opacity:.4}


        .footer{ display:block; margin-top:1px;  border-bottom:1px dotted #7A97B7; border-top:1px solid #7A97B7}
        .social-icons ul{margin:15px auto; display:block; padding:0}
        .social-icons li {
            float: left!important;
            list-style-type:none;
            background-color: #f0f0f0;
            padding:10px 15px 10px 15px;
            margin-left:10px;
        }
        .social-icons{
            display: contents;
        }
        .social-icons li a i{ font-size:18px; color:#666}
        .f-slide .f-l, .f-slide{
            height:  auto !important;
        }
        .f-slide {
            padding-bottom: 70px !important;
        }
    </style>
</head>
<body>



<nav class="navbar navbar-light" style="background-color: #e3f2fd;">
    <!-- Navbar content -->
</nav>
<div class="container-fluid top-head" data-sticky="true">
    <div class="container" style="background:none;">
        <div class="row">
            <div class="col-md-3 logo">
                <img src="{{asset($logo)}}" width="66">
                <h2 style="font-size:23px">{{$appName}}</h2>
            </div>
            <div class="col-md-9">
                <nav class="navbar navbar-expand-lg navbar-light pull-right">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">

                            {{showMainMenu($menus)}}

                            @if(!dAuth()->check())
                            <li class="nav-item" style="border-right: 0">
                                <a class="nav-link " href="{{url('users/login')}}"><i style="font-size: 20px" class="fa fa-sign-in" ></i></a>
                            </li>
                            @else
                            <li class="nav-item dropdown" style="border-right: 0">
                                <a class="nav-link dropdown-toggle" href="#" id="services" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i style="font-size: 20px"  class="fa fa-user"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="services">
                                    <a href="{{url('users/profile')}}" class="dropdown-item">
                                        <i class="fa fa-user"></i> Your Profile
                                    </a>
                                    <a href="{{url('users/articles')}}" class="dropdown-item">
                                        <i class="fa fa-newspaper-o" ></i> Articles
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{url('users/logout')}}" class="dropdown-item">
                                        <i class="fa fa-sign-out" ></i> Logout
                                    </a>
                                </div>
                            </li>
                            @endif

                            
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>


<div class="clearfix"></div>
<div class="container-fluid f-slide">
    <div class="container">
        <div class="row">

            <div class="col-md-6 f-l">
                <section class="main">
                    <h2 class="text404">
                        <span class="text404-mid"><span class="e">Error 404!</span> Page Not Found.	</span>
                    </h2>
                </section>
                <section class="desc">
                    <h4>The page you are looking for was moved, removed or might never existed. You can search as last try.</h4>
                </section>
                <section class="search">
                    <form id="search" action="https://www.learngrammar.net/search" method="post">
                        <input type="text" placeholder="Type and Press Enter Button..." size="40" name="key_word">
                        <input class="search-submit" type="submit" value="">
                    </form>
                </section>
                <section class="last">
                    <a class="btn btn-default" href="javascript:window.history.back()"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
                        <span class="or">or</span> <a class="btn btn-default" href="{{url('/')}}"> Home <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                </section>
            </div>

            <div class="col-md-6 f-r" style="background:url('{{url($logo)}}') !important; background-repeat:no-repeat !important;background-position: center !important;">

            </div>

        </div>
    </div>
</div>
<div class="footer">
    <div class="container">
        <div class="col-md-12">
            <div class="row">
                <div class="social-icons">
                    <ul>
                        <li class="home">
                            <a href="{{url('/')}}"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="contact">
                            <a href=""><i class="fa fa-envelope"></i></a>
                        </li>
                        <li class="twitter">
                            <a href="" target="_blank"><i class="fa fa-twitter-square"></i></a>
                        </li>
                        <li class="facebook">
                            <a href="" target="_blank"><i class="fa fa-facebook-square"></i></a>
                        </li>
                        <li class="googleplus ">
                            <a href="" target="_blank"><i class="fa fa-google-plus-square"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
