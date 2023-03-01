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
							<label for="h_name" class="col-sm-1 col-form-label"> Role <code>*</code></label>

							<div class="col-sm-3">

								<select id="role" name="role" class="form-control" >
									<option value=""> Select User</option>
									@foreach(\App\Roles::get() as $role)
										@php
											$permissions =  str_replace('.','_',$role->permissions);
											$permissions = json_decode($permissions);
										@endphp

                                        <option {{ isset($roleId) && $roleId == $role->id ? 'selected' : '' }} value="{{$role->id}}">{{ucfirst($role->name)}}</option>

									@endforeach;
								</select>

							</div>

							<label for="section" class="col-sm-1 col-form-label">Section<code>*</code></label>

							<div class="col-sm-3">
								<select id="section" name="section" class="form-control" >
									<option value=""> Select Section </option>
                                    @if(!empty($sections))
									@foreach($sections as $section)

                                        <option {{ isset($sectionId) && $sectionId == $section->section_id ? 'selected' : '' }} value="{{$section->section_id}}">{{ucfirst($section->section_name)}}</option>
									@endforeach;
                                    @endif
								</select>
							</div>


						<button type="submit" class="btn btn-primary">Save</button>&nbsp;&nbsp;

						</div>

	  			</form>



    <table class="table table-bordered">

        <tr>
            <th width="15%">User Role</th>
            <th width="15%">Section Name</th>
            <th width="60%">Permissions</th>
			<th width="12%" class="text-center">Action</th>
        </tr>

			@if( $sectionNames->count() > 0)

				@foreach($sectionNames as $sectionName)

					@php
						$sectionPermission = json_decode($sectionName->section_roles_permission);
						//see($sectionPermission);

					@endphp

					@if(in_array($roleId, $sectionPermission) )

					<tr>

					<td>{{ ucfirst($rolePermissions->name) }} </td>
					<td>{{ $sectionName->section_name }} </td>

					@php

                           $actions = json_decode($sectionName->section_action_route);

					@endphp

					<td>
					<div class="row">
						@foreach($actions as $key => $value)

							@php
								$checked = '';
							@endphp

							@if(in_array($roleId, $value))

								<!-- check whether permission exist and is it set true  -->

								@if($rolePermissions->permissions)
								@if(array_key_exists($key, $rolePermissions->permissions))
									@php
										$checked = $rolePermissions->permissions[$key] ? "checked" : "";
									@endphp
								@endif
								@endif

                                @php


                                    //dd($actionName);
                                    $actionName = substr($key, strrpos($key, '.') );
                                    $actionName = trim($actionName,'.');

                                @endphp
								<div class="col-4 mb-2">
								<input id="" type="checkbox" class="role-permission" {{$checked}} data-page="{{$key}}" data-section="{{$sectionName->section_name }}" data-name="{{ ucfirst(str_replace('_',' ',$actionName))  }}" data-action="{{$key}}" data-role="{{$rolePermissions->name}}">


								<label class="form-check-label">{{ ucfirst(str_replace('_',' ',$actionName))  }}</label>&nbsp;
								</div>


							@endif

						@endforeach
					</div>
					</td>


					<td>

					</td>

				 </tr>

					 @endif



				@endforeach
			@endif




	</table>




<!--							<tr>
								<td colspan="4">There is no assigned permission for this role.</td>
							</tr>-->
<p>&nbsp;</p>


	</div>
   </div> <!--/card-->






	</div>
 </div>
</section>

    @endsection
@push('js')
    <script>
        $(document).ready(function(){
            $(document).on('click', '.role-permission', function(){
                var roleName = $(this).attr('data-role');
                var action = $(this).attr('data-action');
                var name = $(this).attr('data-name');
                var section = $(this).attr('data-section');
                var val = 0;
                if($(this).is(':checked')){ val = 1; }
                $.ajax({
                    type: "post",
                    url: "{{ url('author/permissions/assign') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        role    : roleName,
                        action  : action,
                        value   : val,
                        section : section,
                        name    : name
                    },
                    success: function(){
                        console.log('Assigned - ' + action + ' = ' + val + ' - to ' + roleName);
                    },
                    error: function(err){
                        console.log(err);
                    }
                });
            });
        });
    </script>
@endpush
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

    </style>
@endpush
