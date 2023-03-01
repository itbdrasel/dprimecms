@push('css')
    <style>
        input.form-control.float-left.search_input{
            width: 250px;
        }
        ul.pagination{
            float: right;
        }
        .table .mtitle{ font-size: 17px !important;}
        .malias{ font-size: 13px; color: #666; display: block; font-style: italic;    }
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
						<input type="text" name="filter" value="{{ $filter ?? '' }}" placeholder="Filter by menu title..." class="form-control float-left search_input"/>
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
                $total_row=7;
                @endphp
                <tr>
					<th width="30%">Title</th>
					<th width="5%" class="text-center">&uarr; &darr;</th>
                    <th width="25%">Link</th>
                    <th class="text-center">Menu Type</th>
                    <th width="5%" class="text-center">Status</th>
                    <th width="5%"  class="sort text-center" data-row="order" id="order" class="text-center">Order</th>
                    <th class="text-center" width="15%">Manage</th>
                </tr>
                </thead>
                <tbody>
                @if ($allData->count() > 0)

				@php
					$c = 1;
                    function formatMenus($menu, $parent){
                            $menu2 = [];
                            foreach($menu as $i => $item){
                                 $menu2[$item->parent][]=$item;
                            }
                            return $menu2[$parent]??[];
                        }
                    //dd(formatMenus($parent_menu, 23))
                    function menuTable($parent_menu, $parent,$line='&nbsp &nbsp - '){
                        $parent_menus = formatMenus($parent_menu, $parent);
                        $sl =0;
                        foreach($parent_menus as $key=>$data){

                            $orderU='';
                            $orderD='';
                            $sl++;
                            if (count($parent_menus) >1) {
                                if ($sl >1) {
                                  $orderU ='<a onclick="orderBy('.$data->m_id.','.$parent_menus[$sl-2]->m_id.')" style="cursor: pointer">&uarr; </a>';
                                }
                                if (count($parent_menus) != $sl) {
                                   $orderD ='<a onclick="orderBy('.$data->m_id.','.$parent_menus[$sl]->m_id.')" style="cursor: pointer">&darr; </a>';
                                }
                            }

                               $status = '<i class="fa fa-times-circle" aria-hidden="true" style="color:red; font-size:19px"></i>';
                                if ($data->m_status =='active') {
                                    $status = '<i class="fa fa-check-circle" aria-hidden="true" style="color:green;font-size:19px"></i>';
                                }
                               echo '<tr>
                                        <td class="mtitle"><a href="'.url('author/menu/'.$data->m_id).'/edit">'.$line. $data->m_title.'</a> <span class="malias">'.$data->m_alias.'</span></td>
                                        <td class="text-center">'.$orderU.$orderD.'</td>
                                        <td>'.$data->m_link .'</td>
                                        <td class="text-center">'.$data->MenuType->mt_title.'</td>
                                        <td class="text-center">'.$status.'</td>
                                        <td class="text-center">'.$data->m_order.'</td>
                                        <td class="text-center">
                                           <div class="btn-group">
                                            <button type="button" class="btn btn-outline-info">
                                                <a href="'.url('author/menu/'.$data->m_id).'/edit"><i class="fa fa-edit"></i></a>
                                            </button>
                                            <button type="button" class="btn btn-outline-info">
                                                <a id="action" data-toggle="modal" data-target="#windowmodal" href="'.url('author/menu/delete/'.$data->m_id).'"><i class="fa fa-trash"></i></a>
                                            </button>
                                        </div>
                                        </td>
                                    </tr>';

                               menuTable($parent_menu, $data->m_id,'&nbsp &nbsp'.$line.'- ');
                            }
                        $l='';
                        }
				@endphp
				@foreach ($allData as $data)
                    @php

                    $status = '<i class="fa fa-times-circle" aria-hidden="true" style="color:red; font-size:19px"></i>';
                    if ($data->m_status =='active') {
                        $status = '<i class="fa fa-check-circle" aria-hidden="true" style="color:green;font-size:19px"></i>';
                    }
                    $parent_menus = formatMenus($parent_menu, $data->m_id);
                    @endphp
                    <tr>
						<td class="mtitle"><a href="{{url($bUrl.'/'.$data->$tableID.'/edit')}}">{{$data->m_title }}</a> <span class="malias">{{$data->m_alias}}</span></td>
						<td class="text-center">
                            @if ($c+$serial != 1)

                            <a onclick="orderBy('{{$data->m_id}}','{{$allData[$c+$serial-2]->m_id}}')" style="cursor: pointer">&uarr; </a>
                            @endif
                            @if($c+$serial != count($allData))
                            <a onclick="orderBy('{{$data->m_id}}','{{$allData[$c+$serial]->m_id}}')" style="cursor: pointer"> &darr;</a>
                            @endif
                        </td>
						<td>{{ $data->m_link }}</td>
                        <td class="text-center">{{$data->MenuType->mt_title??''}}</td>
                        <td class="text-center">{!! $status !!}</td>
                        <td class="text-center">{{$data->m_order}}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-info">
                                    <a href="{{url($bUrl.'/'.$data->$tableID.'/edit')}}"><i class="fa fa-edit"></i></a>
								</button>
                                <button type="button" class="btn btn-outline-info">
                                    <a id="action" data-toggle="modal" data-target="#windowmodal" href="{{url($bUrl.'/delete/'.$data->$tableID)}}"><i class="fa fa-trash"></i></a>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @if (!empty($parent_menus))
                        {{menuTable($parent_menu, $data->m_id)}}
                    @endif

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

	function orderBy(id, change_id) {
        $.ajax({
            url: APP_URL +"/author/order-by",
            type: "get",
            data: {id: id, change_id:change_id},
            success: function (data) {
                window.location.href = '{{ url($bUrl) }}';
            }
        });
    }
</script>

@endpush
