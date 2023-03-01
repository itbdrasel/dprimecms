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
							<label for="" class="col-sm-3 col-form-label">Title <code>*</code></label>
							<div class="col-sm-4">
								<input type="text" value="{{getValue('h_title', $objData)}}" name="h_title" class="form-control">

							</div>

							<label for="location" class="col-sm-2 col-form-label">Order <code>*</code></label>
							<div class="col-sm-3">
                                <input type="text" value="{{getValue('h_order', $objData)}}" name="h_order" class="form-control">
							</div>

						</div>


						<div class="form-group row">
{{--							<label class="col-sm-3 col-form-label">Content</label>--}}
							<div class="col-sm-12">
								<textarea name="h_content"  class="form-control editor" rows="3" placeholder="Content ..." style="margin-top: 0px; margin-bottom: 0px; height: 172px;">{{getValue('h_content',$objData)}}</textarea>

							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Status<code>*</code></label>
							<div class="col-sm-4">
								<select name="h_status" class="form-control">
									<option {{(getValue('h_status', $objData)==1)?'selected':''}} value="1">Active</option>
									<option {{(getValue('h_status', $objData)==0)?'selected':''}}  value="0">Inactive</option>
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
					<button type="submit"  name="submit" class="btn btn-success">Save </button>&nbsp;&nbsp;
					<button name="submit" value="exit" type="submit"  class="btn btn-primary">Save Exit</button>&nbsp;&nbsp;
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
