@push('css')
    <style>
        input.form-control.float-left.search_input{
            width: 250px;
        }
        ul.pagination{
            float: right;
        }
    </style>
@endpush
@extends("sourcebit::author.master")
@section("content")
<!-- Main content -->
<section class="content data-body">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
				<h2 class="card-title"> {!! $page_icon !!} &nbsp; {{ $title }} </h2>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>

                <button type="button" class="btn btn-tool" >
                    <a href="{{url($bUrl.'/create')}}" class="btn bg-gradient-info btn-sm custom_btn"><i class="mdi mdi-plus"></i> <i class="fa fa-plus-circle"></i> Add New </a>
                </button>
            </div>
        </div>

        <div class="card-body" id="">
            <div class="col-md-10">
                <form action="{{url($bUrl)}}" method="get"  class="form-inline">
                    <div class="form-row">
                        <div class="col">
						<input type="text" name="filter" value="{{ $filter ?? '' }}" placeholder="Filter by title..." class="form-control float-left search_input"/>
						</div>
					 	<div class="col">
						<input  type="submit" class="btn btn-primary" value="Filter"/>
						&nbsp;<a class="btn btn-default" href="{{ url($bUrl) }}"> Reset </a>
						</div>
                    </div>
                </form>

			<div class="col">

			@if( !empty( Request::query() ) )

			 @if( array_key_exists( 'filter', Request::query() ) || array_key_exists( 'location', Request::query() ) )

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
                @php
                $total_row=6;
                @endphp
                <tr>
                    <th class="text-center">SL</th>
					<th class="sort text-left" data-row="title" id="title" >Title</th>
                    <th>Alias</th>
                    <th>Description</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Manage</th>
                </tr>
                </thead>
                <tbody>
                @if ($allData->count() > 0)

				@php
					$c = 1;
				@endphp

				@foreach ($allData as $data)
                    @php

                    $status = '<i class="fa fa-times-circle" aria-hidden="true" style="color:red; font-size:19px"></i>';
                    if (strtolower($data->cat_status) =='active') {
                        $status = '<i class="fa fa-check-circle" aria-hidden="true" style="color:green;font-size:19px"></i>';
                    }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $c+$serial }}</td>
						<td>{{ $data->cat_title }}</td>
						<td>{{ $data->cat_alias }}</td>
                        <td>{{ (!empty($data->cat_description)) ? strip_tags(Str::words($data->cat_description,5,'...')) : '...' }}</td>
                        <td class="text-center">{!! $status !!}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-info">
									<a href="{{url($bUrl.'/'.$data->$tableID)}}"><i class="fa fa-table"></i> </a>
								</button>

                                <button type="button" class="btn btn-outline-info dropdown-toggle dropdown-hover dropdown-icon" data-toggle="dropdown">
                                </button>
                                <div class="dropdown-menu" role="menu" style="">
                                    <a class="dropdown-item" href="{{url($bUrl.'/'.$data->$tableID.'/edit')}}"><i class="fa fa-edit"></i> Edit</a>

                                    <div class="dropdown-divider"></div>

									<a class="dropdown-item" id="action" data-toggle="modal" data-target="#windowmodal" href="{{url($bUrl.'/delete/'.$data->$tableID)}}"><i class="fa fa-trash"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>

					@php
						$c++;
					@endphp

                @endforeach

				@else
					<tr> <td colspan="{{$total_row}}">There is nothing found.</td> </tr>

				@endif
                </tbody>
            </table>
			</div>

            @include('sourcebit::author.layouts.per_page')

		 <div class="col-md-9">
			<div class="pagination_table">
                {!! $allData->render() !!}
            </div>
		 </div>


		 </div><!-- /row -->


	  </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{$title}}
        </div>
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->



<!-- Modal -->
<div class="modal fade" id="windowmodal" tabindex="-1" role="dialog" aria-labelledby="windowmodal" aria-hidden="true">
    <div class="modal-dialog modal-lg"  role="document">
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
	$(document).ready(function(){
		$('#per_page').on('change', function() {

		  $.ajax({
			 type:'POST',
			 url:'{{ url($bUrl) }}',
			 data: $( this ).serialize(),
			 headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			 success: function(data){
			 	window.location.href = '{{ url($bUrl) }}';
			 }
		  });


		});

	});
</script>

@endpush
