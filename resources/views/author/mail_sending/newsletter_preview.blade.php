@push('css')
	<style>
		.select2-container--default .select2-selection--multiple .select2-selection__choice{
			color: #000!important;
		}
		.input-group-text{ border-color: #acb1b7 !important; cursor: pointer;}
	</style>
@endpush


@extends("sourcebit::author.master")
@section("content")
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
		<form method="post" action="{{url($bUrl.'/newsletter-send-email')}}" enctype="multipart/form-data" >
			@csrf
		<div class="card">
			<div class="card-header">
				<h2 class="card-title"> {!! $page_icon !!} &nbsp; {{ $title }} </h2>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
						<i class="fas fa-minus"></i>
					</button>

					<button type="button" class="btn btn-tool" >
						<a href="{{url($bUrl)}}" class="btn btn-info btn-sm"><i class="mdi mdi-plus"></i> <i class="fa fa-arrow-left"></i> Back</a>
					</button>-
				</div>
			</div>
			<div class="card-body">

				<div class="col-md-11">
					{!! validation_errors($errors) !!}
					
					@if (!empty($article_id) && count($article_id) >0)
					    @foreach($article_id as $key=>$value)
							<input type="hidden" name="articles[]" value="{{$value}}">
						@endforeach
					@endif
					<input type="hidden" name="subject" value="{{$subject}}">
					<iframe src="{{url($src)}}" height="700" width="100%" title="Iframe Example"></iframe>
				</div>
			</div>
            <div class="card-footer">
                <div class="offset-md-3 col-sm-9">
                    @php
						$spinner=  '<i class="fas fa-spinner fa-pulse"></i> Please wait... ';
                    @endphp
                    <button type="submit" onclick="this.disabled=true;this. innerHTML='{{$spinner}}';this.form.submit();" class="btn btn-success">Send Email</button>&nbsp;&nbsp;
                    <a href="{{url($bUrl)}}"  class="btn btn-warning">Cancel</a>
                </div>
            </div>
		</div>
		<!-- /.card-body -->

		</div>

        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

