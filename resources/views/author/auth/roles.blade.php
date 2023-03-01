@extends("sourcebit::author.master")
@section("content")

<!-- Main content -->
<section class="container">

	<div class="row data-body">

	  <div class="col-8">

		<div class="card">
			<div class="card-header">
				<h3 class="card-title">  <i class="fa fa-book"></i> {{$title}}</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
						<i class="fas fa-minus"></i>
					</button>
					<button type="button" class="btn btn-tool">
						<a href="{{url('author/role-create')}}" class="btn btn-info btn-sm"><i class="mdi mdi-plus"></i> <i class="fa fa-plus-circle"></i> Add New Role</a>
					</button>
				</div>
			</div>

            <div class="card-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Role Name</th>
							<th>Role Slug</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($roles))
							@foreach($roles as $key=>$role)
						<tr>
							<td>{{++$key}}</td>
							<td>{{$role->name}}</td>
							<td>{{$role->slug}}</td>
						</tr>
							@endforeach
						@endif
					</tbody>
				</table>
				 <!-- /.card-body -->
				<br>
				<div class="card-footer">
					{{$title}}
				</div>
					<!-- /.card-footer-->

			</div>
		</div><!--/card-->

	</div>
 </div>
</section>

    @endsection
