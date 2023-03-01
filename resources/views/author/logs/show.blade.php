@extends("sourcebit::author.master")
@section("content")

<!-- Main content -->
<section class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"> {!! $page_icon !!} &nbsp; {{ $title }} </h2>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                        <button type="button" class="btn btn-tool" >
                            <a href="{{url($bUrl)}}" class="btn btn-info btn-sm"><i class="mdi mdi-plus"></i> <i class="fa fa-arrow-left"></i> Back</a>
                        </button>

                    </div>
                </div>
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right" >Log Title</label>
                        <span class="col-sm-9 col-form-label">
				            {{$objData->log_title}}
			            </span>
                        <label class="col-sm-3 col-form-label text-right" >Log Type</label>
                        <span class="col-sm-9 col-form-label">
				            {{$objData->log_type}}
			            </span>
                        @if (!empty($objData->log_amount))

                        <label class="col-sm-3 col-form-label text-right" >Log Amount</label>
                        <span class="col-sm-9 col-form-label">
				            {{$objData->log_amount}}
			            </span>
                        @endif
                        @if (!empty($objData->user))

                            <label class="col-sm-3 col-form-label text-right" >Log User</label>
                            <span class="col-sm-9 col-form-label">
				            {{$objData->user->full_name??''}}
			            </span>
                        @endif
                        <label class="col-sm-3 col-form-label text-right" >Ip Address</label>
                        <span class="col-sm-9 col-form-label">
				            {{$objData->log_ip}}
			            </span>
                        <label class="col-sm-3 col-form-label text-right" >Log Date</label>
                        <span class="col-sm-9 col-form-label">
				            {{$objData->log_date}}
			            </span>
                    </div>
                </div>
                <div class="card-footer">{{ $title }}</div>
            </div> <!--/card-->
        </div>
     </div>
</section>

    @endsection
