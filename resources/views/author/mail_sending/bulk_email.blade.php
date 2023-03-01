

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
		<form method="post" action="{{url($bUrl.'/bulk-send-email')}}" enctype="multipart/form-data" >
			@csrf
		<div class="card">
			<div class="card-header">
				<h2 class="card-title"> {!! $page_icon !!} &nbsp; {{ $title }} </h2>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
						<i class="fas fa-minus"></i>
					</button>

					{{--<button type="button" class="btn btn-tool" >
						<a href="{{url($bUrl)}}" class="btn btn-info btn-sm"><i class="mdi mdi-plus"></i> <i class="fa fa-arrow-left"></i> Back</a>
					</button>--}}
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
						<label  class="col-sm-2 col-form-label">Template </label>
						<div class="col-sm-5">
							<select name="" id="template" class="form-control">
								@if (count($templates)>0)
									<option value="">Select Template</option>
									@foreach($templates as $key=>$value)
										<option value="{{$value->template}}">{{$value->name}}</option>
									@endforeach
								@endif
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Message </label>
						<div class="col-sm-10">
							<textarea id="message" name="message" cols="40"   class="form-control editor" spellcheck="false">{{getValue('message', $objData)}}</textarea>
						</div>
					</div>

						<div class="form-group row">						
						<label  class="col-sm-2 col-form-label">Category <code>*</code></label>
							<div class="col-sm-5">
                                <select name="category" class="form-control">
                                    <option {{( getValue('category', $objData) == 'all')? 'selected' : '' }} value="all">All</option>
                                    <option {{( getValue('category', $objData) == 1)? 'selected' : '' }} value="1">Verified</option>
                                    <option {{( getValue('category', $objData) == 2)?'selected' : '' }}  value="2">Whitelist</option>
                                    <option {{( old('category') ==='0' || (!empty($objData) && $objData->n_status ===0))?'selected' : '' }}  value="0">Pending </option>
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
		const button = document.querySelector('#template');
		button.addEventListener('change', event => {
			tinymce.activeEditor.setProgressState(true)
			tinymce.activeEditor.setProgressState(false, 30)
			setTimeout(() => {tinymce.activeEditor.setContent(
					$('#template').val()
			)})});
	</script>
@endpush
