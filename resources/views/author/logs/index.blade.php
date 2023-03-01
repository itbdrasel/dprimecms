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

    @php
    function getTypeClassName($type){
        $type = strtolower($type);
        $font = 'fas fa-question-square';
        $className = 'secondary';
        if ($type =='enable') {
           $font = 'fas fa-toggle-on';
           $className = 'success';
        }elseif ($type =='disable'){
            $font = 'fas fa-ban';
            $className = 'danger';
        }elseif ($type =='create'){
            $font = 'fas fa-plus';
            $className = 'info';
        }elseif ($type =='update'){
            $font = 'fa fa-edit';
            $className = 'primary';
        }elseif ($type =='delete'){
            $font = 'fa fa-trash';
            $className = 'danger';
        }
        return [$className, $font];
    }
    function getLogType($type){
       $name = getTypeClassName($type);
        return '<span class="badge badge-'.$name[0].'"><i class="'.$name[1].'"></i> '.$type.'</span>';
    }
    @endphp
    <section class="content">
        <!-- Default box -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"> {!! $page_icon !!} &nbsp; {{ $title }} </h2>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>


                        </div>
                    </div>
                    <div class="card-body data-body">
                        <div class="col-md-10">

                            <form action="{{url($bUrl)}}" method="get"  class="form-inline">

                                <div class="form-row">
                                    <div class="col">
                                        <input type="text" name="filter" value="{{ $filter ?? '' }}" placeholder="Filter by Title & Type..." class="form-control float-left search_input"/>
                                    </div>




                                    <div class="col">
                                        <input type="submit" class="btn btn-primary" value="Filter"/>
                                        &nbsp;<a class="btn btn-default" href="{{ url($bUrl) }}"> Reset </a>
                                    </div>


                                </div>


                            </form>
                            <br>

                            <div class="col">

                                @if( !empty( Request::query() ) )

                                    Showing results for

                                    @if(!empty($filter) )
                                        '{{ $filter }}'
                                    @endif



                                @endif

                            </div>


                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:30px; text-align: center">#</th>
                                    <th width="45%" class="sort" data-row="title" id="name"  >Title</th>
                                    <th class="sort" data-row="type" id="type" >Type</th>
                                    <th data-row="user" id="user"  class="sort">Users </th>
                                    <th >Ip Address </th>
                                    <th width="8%" class="text-center sort" data-row="date" id="date" >Date</th>
                                    <th style="width: 40px">Action</th>
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
                                    <td>{{Str::words($data->log_title,15)}} </td>
                                    <td>{!! getLogType($data->log_type) !!}</td>
                                    <td>{{$data->user->full_name??'' }}</td>
                                    <td>{{$data->log_ip }}</td>
                                    <td class="text-center">{{date('Y-m-d', strtotime($data->log_date))}}</td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-info">
                                                <a  href="{{url($bUrl.'/'.$data->log_id)}}"><i class="fa fa-table"></i> </a>
                                            </button>

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
                    <div class="card-footer">
                        {{$title}}
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@push('js')
    <script>
        $(document).ready(function(){
            $('form').submit(function() {
                $(this).find(":input").filter(function(){return !this.value;}).attr("disabled", "disabled");
            });

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
