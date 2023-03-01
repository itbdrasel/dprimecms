
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
                                <input type="text" name="filter" value="{{ $filter ?? '' }}" placeholder="Filter by name & email..." class="form-control float-left search_input"/>
                            </div>


                            <div class="col">
                                <select class="form-control" name="selected" id="by_loc" class="form-control">

                                    <option value=""> Select User Role </option>
                                    @php $locationList = [] @endphp
                                    @if (!empty($roles))
                                        @foreach ($roles as $key => $role)

                                            @php $locationList += [$role->id => $role->name] @endphp

                                            <option value="{{ $role->id }}"  {{ $selected == $role->id ? 'selected' : '' }} >{{ $role->name }}</option>
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

                            Showing results for

                            @if(!empty($filter) )
                                '{{ $filter }}'
                            @endif

                            @if(!empty($selected) && isset($locationList[$selected]) )
                                @if(!empty($filter) )
                                    &amp;
                                @endif
                                User Role '{{ $locationList[$selected] }}'
                            @endif


                        @endif

                    </div>


                </div>




                <div class="col-md-12 mt-4">

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                @php
                                    $total_row=6;
                                @endphp
                                <thead>
                                <tr>
                                    <th style="text-align: center">#</th>
                                    <th class="sort" data-row="name" id="name"  >Name</th>
                                    <th class="sort" data-row="email" id="email" >Email</th>
                                    <th data-row="role" id="role"  class="text-center sort">Users Role</th>
                                    <th class="text-center sort" data-row="login" id="login" >Last Login</th>
                                    <th class="text-center">Status</th>
                                    <th >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($allData))
                                    @php
                                        $c = 1;
                                    @endphp
                                    @foreach($allData as $key=>$user)
                                        <tr>
                                            <td class="text-center">{{$c}}</td>
                                            <td>{{$user->full_name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td class="text-center">{{ucfirst($user->name) }}</td>
                                            <td class="text-center">{{ $user->last_login ? \Carbon\Carbon::createFromTimeStamp(strtotime($user->last_login))->diffForHumans() : 'Not yet login' }}</td>
                                            <td class="text-center">
                                                @if($user->completed == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-outline-info">
                                                        {{--                                                <a data-toggle="modal" data-target="#windowmodal" href="{{url('system/core/user/show/'.$user->id)}}"><i class="fa fa-table"></i> </a>--}}
                                                        <a  href="{{url($bUrl.'/profile/'.$user->id)}}"><i class="fa fa-table"></i> </a>
                                                    </button>

                                                    <button type="button" class="btn btn-outline-info dropdown-toggle dropdown-hover dropdown-icon" data-toggle="dropdown">
                                                    </button>
                                                    <div class="dropdown-menu" role="menu" style="">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#windowmodal" href="{{url($bUrl.'/'.$user->id.'/edit')}}"><i class="fa fa-edit"></i> Edit</a>
                                                        <a class="dropdown-item" href="{{url($bUrl.'/profile/'.$user->id)}}?permission=permission"><i class="fa fa-eye"></i> view Permission</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" id="action" data-toggle="modal" data-target="#windowmodal" href="{{url($bUrl.'/delete/'.$user->id)}}"><i class="fa fa-trash"></i> Delete</a>
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
                                {!! $allData->links() !!}
{{--                                {{$allData->render() }}--}}
{{--                                {!! $allData->render() !!}--}}
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
