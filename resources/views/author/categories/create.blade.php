

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
							<label for="" class="col-sm-2 col-form-label">Category Title <code>*</code></label>
							<div class="col-sm-5">
								<input type="text" @if (empty($objData)) id="title"@endif  value="{{getValue('cat_title', $objData)}}" name="cat_title" class="form-control form-control-lg">

							</div>

						</div>


						<div class="form-group row">

							<label for="alias" class="col-sm-2 col-form-label">Category Alias <code>*</code></label>
							<div class="col-sm-5">
								<input type="text" id="alias" value="{{getValue('cat_alias', $objData)}}" name="cat_alias" class="form-control">
							</div>

						</div>

						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Category Parent<code>*</code></label>
							<div class="col-sm-5">
								<select name="cat_parent"  class="form-control" >
									<option value="root" {{('root' == getValue('cat_parent',$objData))? 'selected':''}} selected="selected">Root</option>
									@if (!empty($parents))
										@foreach($parents as $parent)
											<option {{($parent->cat_id == getValue('cat_parent',$objData))? 'selected':''}} value="{{$parent->cat_id}}">{{$parent->cat_title}}</option>
										@endforeach
									@endif
								</select>

							</div>
						</div>

						<div class="form-group row">
{{--							<label class="col-sm-2 col-form-label">Description</label>--}}
							<div class="col-sm-12">
								<textarea name="cat_description"  class="form-control editor" rows="3" placeholder="Description ..." style="margin-top: 0px; margin-bottom: 0px; height: 172px;">{{getValue('cat_description',$objData)}}</textarea>

							</div>
						</div>

						<div class="form-group row">
							<label for="h_fax" class="col-sm-2 col-form-label">Status <code>*</code></label>
							<div class="col-sm-5">
								<select name="cat_status" class="form-control">
									<option {{( strtolower(getValue('cat_status', $objData)) == 'active')? 'selected' : '' }} value="Active">Active</option>
									<option {{( strtolower(getValue('cat_status', $objData)) == 'inactive')?'selected' : '' }}  value="Inactive">Inactive</option>
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

@push('plugin')
	<script src="{{url('backend/plugins/tinymce/tinymce.min.js')}}"></script>
@endpush
@push('js')
	<script>

	</script>
@endpush
