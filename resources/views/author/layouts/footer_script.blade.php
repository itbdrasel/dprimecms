<script>var APP_URL = {!! json_encode(url('/')) !!};</script>
<script>var pageUrl = {!! json_encode(url($pageUrl ?? '')) !!};</script>
<!-- jQuery -->
<script src="{{url('backend')}}/plugins/jquery/jquery.min.js"></script>


<script type="text/javascript" src="{{url('/')}}/backend/plugins/timepicker/jquery.timepicker.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/backend/plugins/datepicker/jquery-ui.js"></script>
<!-- Bootstrap 4 -->
<script src="{{url('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- New Added Start -->
<script src="{{url('backend/plugins/moment/moment.min.js') }}"></script>
<script src="{{url('backend/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{url('backend/plugins/date_range_picker/daterangepicker.js') }}"></script>
<!-- New Added End -->

@stack('plugin')

<!-- AdminLTE App -->
<script src="{{url('backend/js/adminlte.min.js')}}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{url('backend/js/demo.js')}}"></script>


<script src="{{url('backend/js/app-helper.js')}}"></script>
<script src="{{url('backend/js/admin-scripts.js')}}"></script>
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()

        //Initialize Select2 Elements
        $('.select2').select2()
        //Date range picker
    });
</script>
@stack('js')

@stack('data_table')
<script src="{{asset('backend/plugins/toastr/toastr.min.js')}}" ></script>
<!--Show Toaster Notification -->
@if((Session::has('success')) || (Session::has('error')) || Session::has('message')))
    <script type="text/javascript">
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "escapeHtml" : true,
        }
        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}');
        @elseif(Session::has('message'))
            toastr.info('{{Session::get('message')}}');
        @elseif(Session::has('error'))
            toastr.error('{{ Session::get('error') }}');
        @endif
    </script>
@endif
