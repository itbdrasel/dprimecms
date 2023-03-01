@push('css')
    <style>
        input.form-control.float-left.search_input{
            width: 250px;
        }
        ul.pagination{
            float: right;
        }
    </style>
@endpush
@extends("sourcebit::author.master")
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
						<td> <strong>E-mail</strong></td>
						<td>  <strong>{{ getValue('n_email', $objData) }} </strong></td>
					</tr>

					<tr>
						<td> Subscription Date </td>
						<td> {{ getValue('created_at', $objData) }} </td>
					</tr>

					<tr>
						<td> IP Address </td>
						<td> {{ getValue('n_ip_address', $objData) }} </td>
					</tr>

					<tr>
						<td> Name </td>
						<td> {{ getValue('n_', $objData) }} </td>
					</tr>

					<tr>
						<td> Key </td>
						<td> {{ getValue('n_key', $objData) }} </td>
					</tr>
					<tr>
						<td> Mail Send to Verify </td>
						<td> {{ getValue('n_verf_mail_count', $objData) }} </td>
					</tr>
					<tr>
						<td> Score </td>
						<td> {{ getValue('n_score', $objData) }} </td>
					</tr>
					<tr>
						<td> Status </td>
						<td> 

						@php
							$status = '<span class="badge badge-warning"><i class="fa fa-times-circle" aria-hidden="true" style="font-size:11px"></i> Pending</span>';
							if ( getValue('n_status', $objData) == 1) {
								$status = '<span class="badge badge-success"><i class="fa fa-check-circle" aria-hidden="true" style="font-size:11px"></i> Varified</span>';
							}
						@endphp

						{!!$status!!}
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



<!-- Modal -->
<div class="modal fade" id="windowmodal" tabindex="-1" role="dialog" aria-labelledby="windowmodal" aria-hidden="true">
  <div class="modal-dialog modal-lg"  role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="windowmodal">&nbsp;</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       		<div class="spinner-border"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



@endsection
