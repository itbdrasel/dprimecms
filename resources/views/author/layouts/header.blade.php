<!DOCTYPE html>
<html lang="en">
<head id="header_aria">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="nofollow">
    <meta name="googlebot" content="noindex">
    <title>{{$title}} | Admin</title>
    <noscript>
        <h1>This page needs JavaScript activated to work.</h1>
        <style>div,footer.main-footer { display:none; }</style>
    </noscript>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    @stack('css_plugin')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{url('backend')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Flaticon -->
    <link rel="stylesheet" href="{{url('backend')}}/fonts/flaticon.css">
<!-- materials icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Theme style -->
    <link rel="stylesheet" href="{{url('backend')}}/css/adminlte.min.css">
    <link rel="stylesheet" href="{{url('backend')}}/css/style.css">
<!-- Toaster Css -->
    <link rel="stylesheet" href="{{url('/')}}/backend/plugins/timepicker/jquery.timepicker.min.css">
    <link rel="stylesheet" href="{{url('/')}}/backend/plugins/datepicker/jquery-ui.css">
    <link href="{{asset('backend/plugins/toastr/toastr.min.css')}}" rel="stylesheet">

<!-- New Added Start -->
    <link rel="stylesheet" href="{{asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('backend/plugins/date_range_picker/daterangepicker.css') }}">
    @stack('css')
<!-- New Added End -->
    <style>
        @media print {
            #print_aria{
                -webkit-print-color-adjust: exact !important;
            }
        }

        .form-control{ border: 1px solid #acb1b7;}
        .alert ul{ margin-bottom: 0px;}        
       
    </style>
</head>
