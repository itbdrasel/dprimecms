@extends("sourcebit::author.master")
@section("content")

<!-- Main content -->
<section class="container">

	<div class="row">

	  <div class="col-12">

		<div class="card">

            <div class="card-body">


			 <form method="post" action="" >
                    @csrf

					{!! validation_errors($errors) !!}



						<div class="form-group row">
							<label for="h_name" class="col-sm-2 col-form-label"> Section Name <code>*</code></label>

							<div class="col-sm-3">
								<input type="text" value="{{old('section_name')}}" name="section_name"  id="section_name"    class="form-control">
							</div>

						</div>


						<div class="form-group row">
							<label for="h_name" class="col-sm-2 col-form-label"> Route Name <code>*</code></label>

							<div class="col-sm-3">
								<input type="text" value="{{old('route_name')}}" name="route_name"  id="route_name"    class="form-control">
							</div>

						</div>




						<div class="form-group row">
							<label class="col-sm-2 col-form-label"> Role <code>*</code></label>


							<div class="col-sm-6">
                                    @if(!empty($roles))
									@foreach($roles as $role)
									<div class="form-check">

										 <input name="role[]"  value="{{$role->id}}"  type="checkbox" class="form-check-input" id="role{{$role->id}}">

										 <label class="form-check-label" for="role{{$role->id}}">{{ucfirst($role->name)}}</label>
									</div>
									@endforeach
                                @endif

							</div>

						</div>


					<!-- /.card-body -->
					<div class="card-footer">
						<div class="offset-md-2 col-sm-9">
							<button type="submit" class="btn btn-primary">Save</button>&nbsp;&nbsp;
							<a href="{{url($pageUrl)}}"  class="btn btn-warning">Cancel</a>
						</div>
					</div>
					<!-- /.card-footer-->

	  			</form>


			</div>
		</div><!--/card-->

	</div>
 </div>
</section>

    @endsection

@push('css')
    <style>
        table.blueTable {
            margin: 10px;
            border: 1px solid #1C6EA4;
            text-align: left;
        }
        table.blueTable td, table.blueTable th {
            border: 1px solid #AAAAAA;
            padding: 5px;
        }

    </style>
@endpush
