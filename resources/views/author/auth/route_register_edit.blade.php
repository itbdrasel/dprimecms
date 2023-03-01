@push('css')
	<style>
		table.blueTable {
			margin: 10px;
			border: 1px solid #1C6EA4;
			text-align: left;
		}
		table.blueTable td, table.blueTable th {
			border: 1px solid #AAAAAA;
			padding: 5px 5px;
		}
		.data-body .table td{ background: transparent !important;}

	</style>
@endpush
@extends("sourcebit::author.master")
@section("content")

<!-- Main content -->
<section class="container">

	<div class="row">

	  <div class="col-12">

		<div class="card">

            <div class="card-body">
				<form method="get" action="" >



					<div class="form-group row">
						<label for="h_name" class="col-sm-2 col-form-label"> Section <code>*</code></label>

						<div class="col-sm-3">

							<select name="section" class="form-control" >
								<option value=""> Select Section </option>
                                @if(!empty($sections))
								@foreach($sections as $section)

									<option {{ isset($sectionId) && $sectionId == $section->section_id ? 'selected' : '' }} value="{{$section->section_id}}">{{ucfirst($section->section_name)}}</option>
								@endforeach;
                                @endif
							</select>

						</div>



						<button type="submit" class="btn btn-primary">Submit</button>&nbsp;&nbsp;

					</div>

				</form>

	  		@if (!empty($route))

			 <form method="post" action="" >
                    @csrf

					{!! validation_errors($errors) !!}



						<div class="form-group row">
							<label for="h_name" class="col-sm-2 col-form-label"> Section Name <code>*</code></label>

							<div class="col-sm-3">
								<input type="text" value="{{getValue('section_name',$route)}}" name="section_name"  id="section_name"    class="form-control">
								<input type="hidden" value="{{getValue('section_id',$route)}}" id="section_id" name="section_id"  >
							</div>

						</div>
						<div class="form-group row data-body">
							<div class="col-sm-12">
							<table class="table table-bordered other_guest">
								<thead>
									<tr>
										<th width="30%">Route Name</th>
										<th>Role</th>
										<th width="10">Action</th>
									</tr>
								</thead>
								<tbody>
								@php
									$actions = json_decode($route->section_action_route);
									$sl =1;
									$array_key= 0;
								@endphp
								@foreach($actions as $key => $value)

									<tr class="route_{{++$sl}} " @if (! Route::has($key) ) style="background-color: #f2dede;" @endif>
										<td>
											<input type="text" value="{{$key}}" id="route_name_{{$sl}}" onblur="routeCheck({{$sl}})" name="route_name[{{$array_key}}]" class="form-control">
											<input type="hidden" id="old_route_name_{{$sl}}" value="{{$key}}">
										</td>
										<td>
											<div class="row">
                                                @if (!empty($roles))
												@foreach($roles as $role_key=>$role)
													@php
														$checked = in_array($role->id, $value) ? "checked" : "";
													@endphp
												<div class="col-4 mb-2">
													<input id="role_{{$sl.'_'.$role->id}}" {{$checked}} value="{{$role->id}}" type="checkbox" name="roles[{{$array_key}}][]" class="role-permission" >
													<label for="role_{{$sl.'_'.$role->id}}" class="form-check-label">{{ucfirst($role->name)}}</label>&nbsp;
												</div>
												@endforeach
                                                @endif
											</div>
										</td>
										<td>
											<div class="guest-info-btn"><span class="remove bg-danger" id="route_{{$sl}}" data-value="{{$key}}" onclick="removeItem({{$sl}})" style="cursor: pointer"><i class="fas fa-times"></i></span></div>
										</td>
									</tr>
									@php
										$array_key++;
									@endphp
								@endforeach
								</tbody>

							</table>
							</div>

{{--							<div class="col-sm-3">--}}
{{--								<input type="text" value="{{old('route_name')}}" name="route_name"  id="route_name"    class="form-control">--}}
{{--							</div>--}}

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
				@endif

			</div>
		</div><!--/card-->

	</div>
 </div>
</section>

    @endsection
@push('js')
    <script>
		function removeItem(id) {
			var route = $('#route_'+id).data('value');
			var section_id = $('#section_id').val();
			if (confirm('Do you wont to remove?')) {
				$.ajax({
					url: '{{url('author/permissions/route_remove')}}',
					data:{ id:section_id, route:route},
					type: 'get',
					success: function (data) {
						$('.route_'+id).remove();
					}
				});
			}
		}
		function routeCheck(id) {
			var route_name = $('#route_name_'+id).val();
			var old_route_name = $('#old_route_name_'+id).val();
			$.ajax({
				url: '{{url('author/permissions/route_check')}}',
				data:{route_name:route_name},
				type: 'get',
				dataType:"json",
				success: function (data) {
					if (data.error == false){
						$('#route_name_'+id).val(old_route_name);
						toastr.error(data.message);
					}else {
						$('#old_route_name_'+id).val(route_name);
					}

				}
			});
		}
    </script>
@endpush

