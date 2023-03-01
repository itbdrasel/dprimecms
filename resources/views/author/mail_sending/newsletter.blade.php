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
		<form method="get" action="{{url($bUrl.'/newsletter-preview')}}" enctype="multipart/form-data" >
{{--			@csrf--}}
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

						<input type="hidden"  value="{{ getValue($tableID, $objData) }}" id="id" name="{{ $tableID }}">

					<div class="form-group row">
						<label for="" class="col-sm-2 col-form-label">Subject <code>*</code></label>
						<div class="col-sm-5">
							<input type="text" id="subject" value="{{getValue('subject', $objData)}}" name="subject" class="form-control">

						</div>
					</div>
					<div class="form-group row">
						<label  class="col-sm-2 col-form-label">Articles </label>
						<div class="col-sm-5">
							<select name="articles[]"  class="select2" multiple="multiple" data-placeholder="Select Articles" style="width: 100%;"  >
								@if (count($articles)>0)
									@foreach($articles as $key=>$value)
										<option value="{{$value->id}}">{{$value->title}}</option>
									@endforeach
								@endif
							</select>
						</div>
					</div>
				</div>
			</div>
            <div class="card-footer">
                <div class="offset-md-3 col-sm-9">
                    @php
						$spinner=  '<i class="fas fa-spinner fa-pulse"></i> Please wait... ';
                    @endphp
                    <button type="submit" onclick="this.disabled=true;this. innerHTML='{{$spinner}}';this.form.submit();" class="btn btn-primary">Next</button>&nbsp;&nbsp;
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

