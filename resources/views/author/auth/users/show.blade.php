
	<div class="modal-content">
		<div class="modal-header">
			<input type="hidden" class="datepickerNone">
			<h4 class="modal-title" id="myModalLabel"> {{$title}} </h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		</div>
	<div class="modal-body">
		<div class="form-group row">
			<label class="col-sm-3 col-form-label text-right" >Name</label>
			<span class="col-sm-9 col-form-label">
				{{$objData->full_name}}
			</span>
			<label class="col-sm-3 col-form-label text-right" >Email</label>
			<span class="col-sm-9 col-form-label">
			{{$objData->email}}
			</span>
			<label class="col-sm-3 col-form-label text-right" >Users Role</label>
			<span class="col-sm-9 col-form-label">
			{{$objData->role->roleName->name}}
			</span>
			<label class="col-sm-3 col-form-label text-right" >Last Login</label>
			<span class="col-sm-9 col-form-label">
				{{$objData->last_login}}
			</span>
		</div>
		<div class="form-group row">

		</div>
	</div>
	<div class="modal-footer">
		<button type="button"  data-reload="true" class="btn btn-secondary dismiss" data-dismiss="modal">Close</button>
	</div>




