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
                    <form action="{{url($bUrl)}}" method="get" name="filter-form"  class="form-inline">
                        <div class="form-row">
                            <div class="col">
                                <input type="text" name="filter" value="{{ $filter ?? '' }}" placeholder="Filter by title..." class="form-control float-left search_input"/>
                            </div>

                            <div class="col">
                                <select class="form-control" name="category" id="by-category" class="form-control">
                                    <option value=""> Select Category </option>
                                    @if ($categories)

                                        @php
                                            $categoryId = $categoryId ?? '';
                                        @endphp

                                        @foreach ($categories as $key => $category)

                                            <option value="{{ $category->cat_id }}"  {{ $categoryId == $category->cat_id ? 'selected' : '' }} >{{ $category->cat_title }}</option>
                                        @endforeach;
                                    @endif
                                </select>
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
                                    $total_row=9;
                                @endphp
                                <tr>
                                    <th width="3%" class="text-center">SL</th>
                                    <th width="24%" class="sort text-left" data-row="title" id="title" >Title</th>
                                    <th width="18%">Alias</th>
                                    <th width="13%"  class="text-center">Category</th>
                                    <th class="text-center">Attributes</th>
                                    <th width="10%"  class="text-center">Created By</th>
                                    <th width="5%" class="text-center"></th>
                                    <th width="5%" class="text-center ">Status</th>
                                    <th width="12%" class="text-center">Manage</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if ($allData->count() > 0 && !empty($allData))
                                    @php
                                        $c = 1;
                                    @endphp

                                    @foreach ($allData as $data)
                                        @php

                                            $status = '<i title="Inactive" class="fa fa-times-circle" aria-hidden="true" style="color:red; font-size:19px"></i>';
                                            if ($data->status =='active') {
                                                $status = '<i title="Active"  class="fa fa-check-circle" aria-hidden="true" style="color:green;font-size:19px"></i>';
                                            }elseif ($data->status =='draft'){
                                                $status = '<i title="Draft" class="fas fa-file-signature text-primary" style="font-size:19px"></i>';
                                            }

                                            $isMenuArticle = '<span class="badge badge-secondary"><i class="fa fa-times-circle" aria-hidden="true"></i> Menu</span>';
                                            if ($data->is_menu == 1) {
                                                $isMenuArticle = '<span title="This post is assigned to menu" class="badge badge-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Menu</span>';
                                            }


                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $c+$serial }}</td>
                                            <td title="Published on {{ date('d M, Y', strtotime($data->created_at)) }} & Updated On {{ date('d M, Y', strtotime($data->updated_at)) }}">
                                                <a href="{{url($bUrl.'/'.$data->$tableID.'/edit')}}">{{ $data->title }}</a>
                                            </td>
                                            <td>{{ $data->alias }}</td>
                                            <td class="text-center">
                                                @php
                                                    $category_title ='';
                                                @endphp
                                                @if(!empty($data->categories) && count($data->categories) >0)
                                                    @foreach($data->categories as $articleCategory)
                                                        @php
                                                            $category_title .= $articleCategory->cat_title.', ' ?? '';
                                                        @endphp
                                                    @endforeach
                                                @endif
                                                {{trim($category_title,', ')}}
                                            </td>



                                            <?php
                                            $ltext = '';
                                            if(count($data->categories) >0 && $data->categories[0]->pivot->r_catlead == 1 ):
                                                $ltext="<i style='color:#4B7113; font-size:15px' class='fa fa-dot-circle'></i>";
                                            elseif(count($data->categories) >0 && $data->categories[0]->pivot->r_catlead == 0 ):
                                                $ltext="<i style='color:#BA8027' class='fa fa-dot-circle'></i>";
                                            else:
                                                $ltext=" <i style='color:#555' class='fa fa-dot-circle'></i>";
                                            endif;



                                            $ttext = '';
                                            $attr = json_decode($data->attribs, true);

                                            $i_featuerd = 0;
                                            if((!isset($attr['featured']) || $attr['featured'] == '0')  ):
                                                $i_featuerd = 1;
                                                $ttext = "<i style='color:#BA8027' class='fa fa-star'></i>";
                                            elseif($attr['featured'] == '1'):
                                                $i_featuerd = 0;
                                                $ttext = "<i style='color:#4B7113; font-size:15px' class='fa fa-star'></i>";
                                            endif;


                                            $stext='';
                                            $attr = json_decode($data->attribs, true);
                                            $i_suggested = 0;
                                            if( (!isset($attr['suggested']) || $attr['suggested'] == '0') ):
                                                $i_suggested = 1;
                                                $stext = "<i style='color:#BA8027' class='fa fa-thumbs-down'></i>";
                                            elseif($attr['suggested'] == '1' ):
                                                $i_suggested = 0;
                                                $stext = "<i style='color:#4B7113; font-size:15px' class='fa fa-thumbs-up'></i>";
                                            endif;
                                            $cat_id =count($data->categories)>0 ?$data->categories[0]['cat_id']:'';

                                            ?>

                                            <td class="text-center">

                                                <a href="{{url($bUrl.'/lead/'.$data->$tableID.'?lead=1&cid='.$cat_id)}}" title='Lead the Category'>{!!$ltext!!}</a>&nbsp;&nbsp;

                                                <a href='{{url($bUrl.'/attribute/'.$data->$tableID.'?featured='.$i_featuerd)}}' title='Featured Posts'>{!!$ttext!!}</a>
                                                &nbsp;&nbsp;

                                                <a href='{{url($bUrl.'/attribute/'.$data->$tableID.'?suggested='.$i_suggested)}}' title='Suggested Post'>{!!$stext!!}</a>

                                            </td>

                                            <td class="text-center" title="">{{ $data->user->full_name ?? $data->created_by }}</td>
                                        <!-- <td class="text-center">{{ date('d-m-Y', strtotime($data->created_at)) }}</td> -->

                                            <td class="text-center">{!! $isMenuArticle !!}</td>

                                            <td class="text-center">{!! $status !!}</td>

                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-info">
                                                        <a href="{{url($bUrl.'/'.$data->$tableID)}}"><i class="fa fa-table"></i> </a>
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle dropdown-hover dropdown-icon" data-toggle="dropdown">
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" role="menu" style="">
                                                        <a class="dropdown-item" href="{{url($bUrl.'/'.$data->$tableID.'/edit')}}"><i class="fa fa-edit"></i> Edit</a>
                                                        <a class="dropdown-item" href="{{url($bUrl.'/post-menu/'.$data->$tableID)}}"><i class="fa fa-edit"></i>Post Menu</a>

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
