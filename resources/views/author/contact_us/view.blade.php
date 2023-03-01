
@push('css')
    <style>
        .mailbox-read-info h3{
            font-size: 28px !important;
        }
        .mailbox-read-time{
            font-size: 15px !important;
        }
    </style>
@endpush
@extends("sourcebit::author.master")
@section("content")

    <section class="content data-body">
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">{!! $page_icon !!} &nbsp; {{ $title }}</h3>


                            </div>
                            <!-- /.card-header -->

                            <div class="card-body p-0">
                                <div class="mailbox-read-info">
                                    <h3 class="mb-3">{{$objData->subject}}</h3>
                                    <h6>From : {{$objData->email}}
                                        <span class="mailbox-read-time float-right">{{date('d M. Y h:i A', strtotime($objData->created_at))}}</span></h6>
                                </div>
                                <!-- /.mailbox-read-info -->
                                <div class="mailbox-controls with-border text-center">

                                </div>
                                <!-- /.mailbox-controls -->
                                <div class="mailbox-read-message">
                                    <div class="card-body">
                                        <p>{{$objData->message}}</p>
                                        <p> {{$objData->name}}</p>
                                        <p>{{$objData->phone}}</p>
                                    </div>
                                </div>
                                <!-- /.mailbox-read-message -->
                            </div>
                            <!-- /.card-body -->

                            <!-- /.card-footer -->
                            <div class="card-footer">
{{--                                <div class="float-right">--}}
{{--                                    <a class="btn btn-default" data-toggle="modal" data-target="#windowmodal" href="{{url($bUrl.'/email-send/'.$objData->$tableID)}}"><i class="fas fa-reply"></i> Reply</a>--}}
{{--                                </div>--}}
                                <a class="btn btn-default"  data-toggle="modal" data-target="#windowmodal" title="Delete" href="{{url($bUrl.'/delete/'.$objData->$tableID)}}"> <i class="far fa-trash-alt"></i> Delete </a>
                                <button type="button" class="btn btn-default"><i class="fas fa-print"></i> Print</button>
                            </div>
                            <!-- /.card-footer -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>


    <!-- Default box -->

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

