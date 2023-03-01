@extends('sourcebit::author.master')
@section('content')
    <section class="content">
        <!-- Default box -->
        <div class="row">

            <div class="col-8">

				<div class="card">
					<div class="card-header">
						<h2 class="card-title"> {{$title}} </h2>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
								<i class="fas fa-minus"></i>
							</button>

						</div>
					</div>

					<form action="{{url('author/change-password')}}" method="post">
						@csrf
					<div class="card-body login-card-body mt-4">
						<div class="pl-3 col-11">

								{{ validation_errors($errors) }}

							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Users<code>*</code></label>

								<div class="col-sm-8">
									<div class="input-group ">
										<select name="user" class="form-control" >
											<option value=""> Select Users </option>
											@foreach($users as $user)
												<option  value="{{$user->id}}" {{ ( old("role") == $user->id ? "selected" : "") }} > {{ ucfirst($user->full_name) }}</option>
											@endforeach

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
								<label class="col-sm-4 col-form-label">New Password<code>*</code></label>

								<div class="col-sm-8">
									<div class="input-group ">
										<input type="password" name="password" id="password" class="form-control" placeholder="New Password">
										<div class="input-group-append">
											<div class="input-group-text">
												<span class="fas fa-lock"></span>
											</div>
										</div>
									</div>
									<p class="text-danger text-justify">Your password must be a minimum length of eight characters, consisting of four of the following - uppercase (A-Z) & lowercase (a-z) alphabetic characters, numeric characters (0-9) & special characters (! @ # $ %) </p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Confirm Password<code>*</code></label>

								<div class="col-sm-8">
									<div class="input-group ">
										<input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
										<div class="input-group-append">
											<div class="input-group-text">
												<span class="fas fa-lock"></span>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>


					</div>
					<!-- /.login-card-body -->
						<br>
						<div class="card-footer">
							<div class="offset-md-4 col-sm-10">
								<button type="submit" class="btn btn-primary">{{$title}}</button>&nbsp;&nbsp;
							</div>
						</div>
					</form>

				</div><!-- /.card -->

	</div>
 </div>
</section>

@endsection
