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
							<label for="" class="col-sm-3 col-form-label">Name <code>*</code></label>
							<div class="col-sm-4">
								<input type="text" @if (empty($objData)) id="name" @endif value="{{getValue('name', $objData)}}" name="name" class="form-control">

							</div>



						</div>


						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Status <code>*</code></label>
							<div class="col-sm-4">
								<?php
								$arrays = [1=>'Active', 2=>'Inactive'];
								?>
								<select name="status" class="form-control">
									@foreach($arrays as $key=>$value)
									<option {{(getValue('status', $objData)==$key)?'selected':''}} value="{{$key}}">{{$value}}</option>
									@endforeach
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