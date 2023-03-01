@push('css')
<style>
input.form-control.float-left.search_input {
    width: 250px;
}

ul.pagination {
    float: right;
}
</style>
@endpush
@extends("sourcebit::author.master")
@section("content")
    <section class="content data-body">
    <!-- Default box -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"> {!! $page_icon !!} &nbsp; {{ $title }} </h2>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" >
                            <a href="{{url($bUrl.'/newsletter')}}" class="btn bg-gradient-success btn-sm custom_btn"><i class="mdi mdi-plus"></i> <i class="fa fa-envelope-open-text"></i> Newsletter </a>
                        </button>
                        <button type="button" class="btn btn-tool" >
                            <a href="{{url($bUrl.'/bulk-email')}}" class="btn bg-gradient-primary btn-sm custom_btn"><i class="mdi mdi-plus"></i> <i class="fa fa-envelope-square"></i> Bulk Send Email </a>
                        </button>
                        <button type="button" class="btn btn-tool" >
                            <a href="{{url($bUrl.'/compose')}}" class="btn bg-gradient-info btn-sm custom_btn"><i class="mdi mdi-plus"></i> <i class="fa fa-envelope-open"></i> Send Email </a>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-10">

                        <form action="{{url($bUrl)}}" method="get" class="form-inline">

                            <div class="form-row">
                                <div class="col">
                                    <input type="text" name="filter" value="{{ $filter ?? '' }}"
                                        placeholder="Filter by Subject..."
                                        class="form-control float-left search_input" />
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <input type="submit" class="btn btn-primary" value="Filter" />
                                        &nbsp;<a class="btn btn-default" href="{{ url($bUrl) }}"> Reset </a>
                                    </div>
                                </div>


                            </div>


                        </form>

                        <div class="col">

                            @if( !empty( Request::query() ) )

                            @if( array_key_exists( 'filter', Request::query() ) )

                            Showing results for

                            @if(!empty($filter) )
                            '{{ $filter }}'
                            @endif

                            @endif

                            @endif

                        </div>


                    </div>

                    <div class="col-md-12 mt-4">

                        <div class="row">
                            <div class="col-md-12">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="40">#</th>
                                            <th class="">Subject</th>
                                            <th class="">Category</th>
{{--                                            <th class="">Send By</th>--}}
                                            <th class="">Date</th>
                                            <th class="text-center" width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($allData))
                                            @php
                                                $c = 1;
                                            @endphp
                                        @foreach($allData as $key=>$data)
                                        <tr>
                                            <td class="text-center">{{ $c+$serial }}</td>
                                            <td>{{$data->mail_subject}}</td>
                                            <td>{{$data->mail_category}}</td>
                                            <td>{{date('d-m-Y', strtotime($data->created_at))}}</td>
                                            <td class=" text-center" >
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-outline-info">
                                                        <a  href="{{url($bUrl.'/'.$data->$tableID)}}"><i class="fa fa-table"></i> </a>
                                                    </button>

                                                    {{--<button type="button" class="btn btn-outline-info">
                                                        <a data-toggle="modal" data-target="#windowmodal"
                                                           href="{{url($bUrl.'/delete/'.$data->$tableID)}}"><i class="fa fa-trash"></i> </a>
                                                    </button>--}}
                                                </div>
                                            </td>
                                        </tr>
                                        @php
                                            $c++;
                                        @endphp
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="row mt-4">
                                    @include('sourcebit::author.layouts.per_page')
                                    <div class="col-md-9">
                                        <div class="pagination_table">
                                            {!! $allData->render() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /row -->


                    </div>

                </div>
                <div class="card-footer">
                    {{$title}}
                </div>
            </div>

        </div>
    </div>
</section>


<!-- Modal -->
<div class="modal fade" id="windowmodal" tabindex="-1" role="dialog" aria-labelledby="windowmodal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="windowmodal">&nbsp;</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="spinner-border"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
$(document).ready(function() {
    $('#per_page').on('change', function() {
        $.ajax({
            type: 'POST',
            url: '{{ url($bUrl) }}',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                window.location.href = '{{ url($bUrl) }}';
            }
        });
    });
});
</script>
@endpush
