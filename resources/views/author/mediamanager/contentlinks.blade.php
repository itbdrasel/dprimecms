@push('css')
    <style>
        input.form-control.float-left.search_input{
            width: 250px;
        }
        ul.pagination{
            float: right;
        }
        .insertable .article-info{ font-size: 14px; color: #555; margin: 0; padding: 0; font-style: italic;}
        .table-hover td{ font-size: 18px;}
        .table-hover td:hover{ background: inherit;}
    </style>
@endpush
@extends("sourcebit::author.mediamanager.layout")
@section("content")
<!-- Main content -->
<section class="content data-body">
    <!-- Default box -->
    <div class="card">


        <div class="card-body" id="">

        <div class="col-md-12">
        <div class="row">
            <div class="col-7">
                 <a class="btn btn-default" href="{{ url('author/mediamanager') }}"> &larr; </a> 
            </div>

            <div class="col">
                <form action="{{url($pageUrl)}}" method="get" >
                    <div class="form-row">                        
                        <div class="col-auto">                        

						<input type="text" name="filter" value="{{ $filter ?? '' }}" placeholder="Filter by title or category..." class="form-control float-left search_input"/>
						</div>
					 	<div class="col-auto">
						<input  type="submit" class="btn btn-primary" value="Filter"/>
						&nbsp;<a class="btn btn-default" href="{{ url($pageUrl) }}"> Reset </a>
						</div>
                    </div>
                </form>

        </div><!-- /row -->
        </div>

        </div><!-- /card-body -->



		<div class="col-md-12 mt-4">

			<div class="row">
				<div class="col-md-12 insertable">

			<table class="table table-bordered table-hover">
                <thead>
                @php
                $total_row=9;
                @endphp
                <tr>
					<th width="22%" class="text-left" data-row="title" id="title" >Content Details</th>            
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
                    if ($data->status =='active') {
                        $status = '<i class="fa fa-check-circle" aria-hidden="true" style="color:green;font-size:19px"></i>';
                    }
                    @endphp
                    <tr>
                        @php
                            $category_title ='';
                            @endphp
                        @if(!empty($data->categories))
                            @foreach($data->categories as $articleCategory)
                                @php
                                    $category_title .= $articleCategory->cat_title.', ' ?? '';
                                @endphp
                            @endforeach
                        @endif
                          
						<td data-path="{{postUrl($data->alias, $data->id)}}">
                            {{$data->title}}
                        <p class="article-info">                                  
                            {{' Category: '.trim($category_title,', ')}}
                        </p>
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


            <div class="col-md-9">
			<div class="pagination_table">
                {!! $allData->render() !!}
            </div>
		 </div>


		 </div><!-- /row -->


	  </div>

        </div>

    </div>
    <!-- /.card -->
</section>
<!-- /.content -->


@endsection
@push('js')
<script>
	$(document).ready(function(){
    
        $(document).on("mouseover",'.insertable', function() {
        
            $('.insertable td').dblclick(function(e) {
                e.preventDefault();
                var path =  $(this).data('path');
                window.parent.postMessage({
                    mceAction: 'mceGeturl',
                    url: path
                }, '*');           		
                
            });
        });
	});
</script>

@endpush
