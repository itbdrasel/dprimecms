
@extends("sourcebit::author.master")
@section("content")
<style>
    .data-body .table tr th{
        background-color: #f7f7f7;
        border-color: #dee2e6;
        font-weight: bold;
        color: #444;
    }
</style>
    <section class="content data-body">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{!! $page_icon !!} &nbsp; {{$objData->title}}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>

                                <button type="button" class="btn btn-tool" >
                                    <a href="{{url($bUrl)}}" class="btn bg-gradient-info btn-sm custom_btn"><i class="mdi mdi-plus"></i> <i class="fa fa-arrow-left"></i> Back </a>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <form method="post" action="{{url($pageUrl)}}" >
                            @csrf
                        <div class="card-body">
                            @php
                                    $post_menus = json_decode($objData->post_menus);
                            @endphp
                            <div class="col-md-12">
                                <h4><a href="{{url($bUrl.'/'.$objData->$tableID)}}">{{$objData->title}}</a></h4>
                                <div class="col-sm-8" id="post_menus">

                                    <div class="form-group row">
                                        <label  class="col-sm-3 col-form-label">Post Menu </label>
                                        <div class="col-sm-3">
                                            <input type="text" placeholder="Text" value="{{$post_menus[0]->text??''}}" name="text[]" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" value="{{$post_menus[0]->id??''}}" placeholder="ID Name" name="id[]" class="form-control">
                                        </div>
                                        <div class="col-sm-1">
                                            <a style="cursor: pointer" class="input_add btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    @if (!empty($post_menus))
                                        @php
                                            $sl =0;
                                        @endphp
                                        @foreach($post_menus as $key=>$value )
                                            @if($sl++ >0)
                                                <div class="form-group row" id="remove_div_{{$sl}}">
                                                    <label  class="col-sm-3 col-form-label"></label>
                                                    <div class="col-sm-3">
                                                        <input type="text" placeholder="Text" value="{{$value->text??''}}" name="text[]" class="form-control">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" value="{{$value->id??''}}" placeholder="ID Name" name="id[]" class="form-control">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <a style="cursor: pointer" onclick="removeDiv({{$sl}})"  class="input_remove btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>


                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="offset-md-3 col-sm-9">
                                @php
                                    $spinner=  '<i class="fas fa-spinner fa-pulse"></i> Save';
                                @endphp
                                <button type="submit" onclick="this.disabled=true;this. innerHTML='{{$spinner}}';this.form.submit();" class="btn btn-primary">Save</button>&nbsp;&nbsp;
                                <a href="{{url($pageUrl)}}"  class="btn btn-warning">Cancel</a>
                            </div>
                        </div>
                        </form>

                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>


    <!-- Default box -->



@endsection

@push('js')
    <script type="text/javascript">
        count = {{count($post_menus??[])}};
        $('.input_add').on('click', function () {
            var html ='' +
                '<div class="form-group row" id="remove_div_'+count+'">' +
                '<label  class="col-sm-3 col-form-label"></label>' +
                '<div class="col-sm-3">' +
                '<input type="text" placeholder="Text" value="" name="text[]" class="form-control">' +
                '</div>' +
                '<div class="col-sm-4">' +
                ' <input type="text" value="" placeholder="ID Name" name="id[]" class="form-control">' +
                '</div>' +
                '<div class="col-sm-1">' +
                '<a style="cursor: pointer" onclick="removeDiv('+count+')"  class="input_remove btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></a>' +
                '</div>' +
                '</div>';
            $('#post_menus').append(html);
            count++;
        })
        function removeDiv(id) {
            $('#remove_div_'+id).remove();
        }
    </script>
@endpush