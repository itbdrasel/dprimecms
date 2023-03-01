@extends("sourcebit::author.master")
@section("content")
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
		<form method="post" action="{{url($bUrl.'/store')}}" enctype="multipart/form-data" >
			@csrf
		<div class="card">
			<div class="card-header">
				<h2 class="card-title"> {!! $page_icon !!} &nbsp; {{ $title }} </h2>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
						<i class="fas fa-minus"></i>
					</button>

					<button type="button" class="btn btn-tool" >
						<a href="{{url($bUrl)}}" class="btn btn-info btn-sm"><i class="mdi mdi-plus"></i> <i class="fa fa-arrow-left"></i> Back</a>
					</button>
				</div>
			</div>
			<div class="card-body">

				<div class="col-md-11">
						{!! validation_errors($errors) !!}
						<input type="hidden"  value="{{ getValue($tableID, $objData) }}" id="id" name="{{ $tableID }}">
						<div class="form-group row">
							<label for="" class="col-sm-3 col-form-label">Title <code>*</code></label>
							<div class="col-sm-4">
								<input type="text" @if (empty($objData)) id="title" @endif value="{{getValue('mt_title', $objData)}}" name="mt_title" class="form-control">

							</div>

							<label for="location" class="col-sm-2 col-form-label">Alias <code>*</code></label>
							<div class="col-sm-3">
                                <input type="text" id="alias" value="{{getValue('mt_alias', $objData)}}" name="mt_alias" class="form-control">
							</div>

						</div>


						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Menu Template/Place <code>*</code></label>
							<div class="col-sm-4">
								<?php
								$templates = ['mainmenu'=>'Main Menu', 'topmenu'=>'Top Menu', 'sidemenu'=>'Side Menu', 'footermenu'=>'Footer Menu'];
								?>
								<select name="mt_tmpl" class="form-control">
									@foreach($templates as $key=>$value)
									<option {{(getValue('mt_tmpl', $objData)==$key)?'selected':''}} value="{{$key}}">{{$value}}</option>
									@endforeach
								</select>

							</div>
						</div>


				</div>
			</div>
            <div class="card-footer">
                <div class="offset-md-3 col-sm-9">
                    @php
                        $spinner=  '<i class="fas fa-spinner fa-pulse"></i> Save';
                    @endphp
                    <button type="submit" onclick="this.disabled=true;this. innerHTML='{{$spinner}}';this.form.submit();" class="btn btn-primary">Save</button>&nbsp;&nbsp;
                    <a href="{{url($bUrl)}}"  class="btn btn-warning">Cancel</a>
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

@push('js')
	<script type="text/javascript">

	</script>
@endpush