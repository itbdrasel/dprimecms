
@extends("author.master")
@section("content")
    <!-- Main content -->
<section class="content data-body">
	<!-- Default box -->		


	<div class="card">

	<div class="card-header">
			<h2 class="card-title"> {!! $page_icon !!} &nbsp; {{ $title }} </h2>
		<div class="card-tools">
			<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
				<i class="fas fa-minus"></i>
			</button>

			<button type="button" class="btn btn-tool" >
				<a href="{{url($bUrl)}}" class="btn bg-gradient-info btn-sm custom_btn"><i class="fa fa-arrow-left"></i> Back </a>
			</button>
		</div>
	</div>		

		<div class="card-body">

			<div class="col-md-8">

				<table class="table table-striped table-hover">
					<tr>
						<td> <strong>Subject</strong></td>
						<td>  <strong>{{ $objData->mail_subject }} </strong></td>
					</tr>
					<tr>
						<td> Category </td>
						<td> {{ $objData->mail_category }} </td>
					</tr>
					<tr>
						<td> Mail Send By </td>
						<td> {{ $objData->user->full_name??'' }} </td>
					</tr>

					<tr>
						<td> Date </td>
						<td> {{ date('d-m-Y', strtotime($objData->created_at)) }} </td>
					</tr>
					<tr>
						<td> Email Address </td>
						@php
						$emails = json_decode($objData->mail_address);
						@endphp
						<td>
							@if (!empty($emails))
							@foreach($emails as $key=>$value)
								<i class="fas fa-check-circle"></i> {{$value}}<br>
							@endforeach
							@endif
						</td>
					</tr>


				</table>

				</div>

				</div>

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
