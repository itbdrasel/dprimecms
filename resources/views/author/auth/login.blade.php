@extends("sourcebit::author.master_auth")
@section("content")
	<style>
		.login-box, .register-box {
			width: 370px !important;
		}
	</style>
	<body>
	<section class="login-section">
		<div class="container-fluid pl-0">
			<div class="row d-flex justify-content-center align-items-center">
				<div class="col-lg-7 col-md-6">

					<div class="login-section-img">
						<h3>{{$appName}}</h3>
					</div>
				</div>
				<div class="col-lg-5 col-md-6 d-flex justify-content-center">
					<div class="login-box">
						<div class="login-box-title">
							<img width="100" src="{{url($logo)}}">
							<h3 class="mt-2">{{$appName}}</h3>
							<h5>Sign in to Continue</h5>
						</div>

						<div class="d-flex align-items-center login-box-title2">
							@if($errors->any())
							<div class="alert alert-danger w-100 alert-dismissible fade show" role="alert" style="background-color: #f8d7da !important; border-color: #f5c2c7; color: #842029;">
								{{$errors->all()[0]??''}}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@endif
						</div>
						<div>
							<div class="login-card-body">
								<form action="{{url('author/login')}}" method="post" class="m-0">
									{{csrf_field()}}
									<div class="row">
										<div class="col-12">
											<div class="form-group mb-3">
												<input type="email"  id="email" name="email" value="{{old('email')}}" class="form-control" placeholder="Email">
												<span class="fas fa-envelope"></span>
											</div>
										</div>
										<div class="col-12">
											<div class="form-group mb-3">
												<input type="password" id="password" name="password" class="form-control" placeholder="Password">
												<span class="fas fa-lock"></span>
											</div>
										</div>
{{--										<div class="col-12 text-left">--}}
{{--											<div class="icheck-primary">--}}
{{--												<input type="checkbox" id="remember">--}}
{{--												<label for="remember">--}}
{{--													Remember Me--}}
{{--												</label>--}}
{{--											</div>--}}
{{--										</div>--}}
										<div class="col-12">
											<button type="submit" class="btn btn-block">Sign In</button>
										</div>
										<div class="col-12">
											<p><a class="text-right" href="{{url('forgot-password')}}">Forget Password?</a>&nbsp;
											&nbsp;
											<a class="text-left"  href="{{url('/')}}">Go to Home</a></p>
										</div>
									</div>
								</form>
							</div>
							<!-- /.login-card-body -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	</body>
@endsection

