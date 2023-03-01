@extends("sourcebit::author.master")
@section("content")

@push('js')
	<style>
		#hotel_list, #module_name{ display:none}
	</style>
@endpush
    <section class="content">
        <!-- Default box -->
        <div class="row">

            <div class="col-12">
				<form action="{{url($bUrl.'/store')}}" method="post">
				<div class="card">
					<div class="card-header">
						<h2 class="card-title">{!! $page_icon !!} {{$title}} </h2>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
								<i class="fas fa-minus"></i>
							</button>

							<button type="button" class="btn btn-tool" >
								<a href="{{url($bUrl)}}" class="btn btn-info btn-sm"><i class="mdi mdi-plus"></i> <i class="fa fa-arrow-left"></i> Back</a>
							</button>
						</div>
					</div>


					<div class="card-body login-card-body mt-4">
						<div class="pl-3 col-7">

								{{csrf_field()}}

								{{ validation_errors($errors) }}

								<div class="form-group row">
									<label class="col-sm-4 col-form-label">User Name<code>*</code></label>

									<div class="col-sm-8">
										<div class="input-group">
											<input type="text" name="full_name" id="full_name" value="{{ old('full_name')}} " class="form-control @error('full_name') is-invalid @enderror" placeholder="Your Name">
											<div class="input-group-append">
												<div class="input-group-text">
													<span class="fas fa-user"></span>
												</div>
											</div>
										</div>

									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">E-mail<code>*</code></label>

									<div class="col-sm-8">
										<div class="input-group ">
											<input type="email" id="email" name="email" value="{{ old('email')}} " class="form-control @error('email') is-invalid @enderror" placeholder="Email">
											<div class="input-group-append">
												<div class="input-group-text">
													<span class="fas fa-envelope"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Phone</label>

                                <div class="col-sm-8">
                                    <div class="input-group ">
                                        <input type="text" id="phone" name="phone" value="{{ old('phone')}} " class="form-control" placeholder="Phone">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-phone-alt"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Password<code>*</code></label>

									<div class="col-sm-8">
										<div class="input-group ">
											<input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
											<div class="input-group-append">
												<div class="input-group-text">
													<span class="fas fa-lock"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Confirm Password<code>*</code></label>

                                <div class="col-sm-8">
                                    <div class="input-group ">
                                        <input id="password_confirmation" class="form-control @error('password') is-invalid @enderror" type="password" placeholder="Confirm Password" name="password_confirmation">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">User Group<code>*</code></label>

									<div class="col-sm-8">
										<div class="input-group ">
											<select id="role" name="role" class="form-control @error('role') is-invalid @enderror" >
												<option value=""> Select User Group </option>
                                                @if(!empty($roles))
												@foreach($roles as $role)
													<option data-role="{{ $role->slug }}" value="{{$role->id}}" {{ ( old("role") == $role->id ? "selected" : "") }} > {{ ucfirst($role->name) }}</option>
												@endforeach
                                                @endif

											</select>
											<div class="input-group-append">
												<div class="input-group-text">
													<span class="fas fa-user-circle"></span>
												</div>
											</div>
										</div>
									</div>
								</div>


							<div class="form-group row">
                                <label class="col-sm-4 col-form-label">Create and Activate</label>

                                <div class="col-sm-8" style="text-align:left">                                    
                                        <input style="width: 5%" id="create_activate" value="true" class="form-control" type="checkbox" name="create_activate">
                                </div>
                            </div>								

						</div>


					</div>
					<!-- /.login-card-body -->
                    <div class="card-footer">
                        <div class="offset-md-4 col-sm-8">
                            @php
                                $spinner=  '<i class="fas fa-spinner fa-pulse"></i> Save';
                            @endphp
                            <button type="submit" onclick="this.disabled=true;this. innerHTML='{{$spinner}}';this.form.submit();" class="btn btn-primary">Save</button>&nbsp;&nbsp;
                            <a href="{{url($bUrl)}}"  class="btn btn-warning">Cancel</a>
                        </div>
                    </div>
				</div><!-- /.card -->
				</form>
	</div>
 </div>
</section>
@endsection

