

@push('css')
	<style>
		#tooltip{position:absolute;right:-2%; top:25%; }
		#tooltip .fa{ font-size:14px; color:#666}
	</style>
@endpush

@extends("sourcebit::author.master")
@section("content")
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
		<form method="post" action="{{url($bUrl.'/store')}}" enctype="multipart/form-data" >
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
					</button>
				</div>
			</div>
			<div class="card-body">

				<div class="col-md-11">

						{!! validation_errors($errors) !!}

						<input type="hidden"  value="{{ getValue($tableID, $objData) }}" id="id" name="{{ $tableID }}">

						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Name </label>
							<div class="col-sm-5">
								<input type="text" id="n_name" value="{{getValue('n_name', $objData)}}" name="n_name" class="form-control">

							</div>

						</div>


						<div class="form-group row">

							<label for="alias" class="col-sm-2 col-form-label">E-mail <code>*</code></label>
							<div class="col-sm-5">
                                <input type="email" id="n_email" value="{{getValue('n_email', $objData)}}" name="n_email" class="form-control">
							</div>

						</div>

						<div class="form-group row">						
						<label for="h_fax" class="col-sm-2 col-form-label">Validate <code>*</code></label>
							@php
							$validateArray = [1=>'Whitelist',2=>'Disabled',3=>'Blacklist',4=>'Unsubscribe'];
							@endphp
							<div class="col-sm-5">
                                <select name="n_validate" class="form-control">
									@foreach($validateArray as $key=>$value)
                                    <option {{( getValue('n_validate', $objData) == $key)? 'selected' : '' }} value="{{$key}}">{{$value}}</option>
									@endforeach
                                </select>
							</div>	
						</div>										


				</div>
			</div>
            <div class="card-footer">
                <div class="offset-md-3 col-sm-9">
                    @php
                        $spinner=  '<i class="fas fa-spinner fa-pulse"></i> Save';
                    @endphp
                    <button type="submit" onclick="this.disabled=true;this. innerHTML='{{$spinner}}';this.form.submit();" class="btn btn-primary">Save</button>&nbsp;&nbsp;
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


@push('js')
	<script>

	</script>
@endpush
