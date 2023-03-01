@extends("sourcebit::author.master")
@section("content")
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
		<form method="post" action="{{url($bUrl.'/store')}}" >
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
							<label for="" class="col-sm-2 col-form-label">Menu Title <code>*</code></label>
							<div class="col-sm-6">
								<input type="text" @if (empty($objData)) id="title" @endif value="{{getValue('m_title', $objData)}}" name="m_title" class="form-control form-control-lg">
							</div>

						</div>

						<div class="form-group row">

							<label for="alias" class="col-sm-2 col-form-label">Menu Alias <code>*</code></label>
							<div class="col-sm-6">
                                <input type="text" id="alias" value="{{getValue('m_alias', $objData)}}" name="m_alias" class="form-control form-control-sm">
							</div>

						</div>						

						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Link Menu With <code>*</code></label>
							<div class="col-sm-6">								

								<div role="tabpanel" id="tabs">
									<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
										<li class="nav-item">
											<a  class="nav-link {{(getValue('m_view', $objData)=='single' ||empty($objData))?'active':''}}" id="Article_tab" data-toggle="pill" href="#article" role="tab" aria-controls="article" aria-selected="{{(getValue('m_view', $objData)=='single' || empty($objData))?'true':'false'}}">Article</a>
										</li>
										<li class="nav-item">
											<a class="nav-link {{(getValue('m_view', $objData)=='category')?'active':''}}" id="category_tab" data-toggle="pill" href="#category" role="tab" aria-controls="category" aria-selected="{{(getValue('m_view', $objData)=='category')?'true':'false'}}">Category</a>
										</li>
										<li class="nav-item">
											<a class="nav-link {{(getValue('m_view', $objData)=='external')?'active':''}}" id="external_tab" data-toggle="pill" href="#external" role="tab" aria-controls="external" aria-selected="{{(getValue('m_view', $objData)=='external')?'true':'false'}}">External</a>
										</li>
									</ul>

									<!-- Tab panes -->
									<div class="tab-content mt-2" id="custom-content-below-tabContent">
										<div class="tab-pane fade {{(getValue('m_view', $objData)=='single' ||empty($objData))?'active show':''}}" id="article" role="tabpanel" aria-labelledby="article_tab">
									
											@if (!empty($objData))
												<input type="hidden"  value="{{ ($objData->m_view == 'single') ? $objData->m_link : ''}}" name="previous_article">
											@endif										

											<select name="plink" class="form-control">
												<option value="">Select Article</option>
												@if (!empty($pages))
												@foreach($pages as $value)
													<option {{(getValue('m_link', $objData) == $value->alias || old('plink') == $value->alias) ? 'selected' : '' }} value="{{$value->alias}}">{{$value->title}}</option>
												@endforeach
												@endif
											</select>
										</div>
										<div class="tab-pane fade {{(getValue('m_view', $objData)=='category')?'active show':''}}" id="category" role="tabpanel" aria-labelledby="category_tab">
											<select name="clink" class="form-control">
												<option value="">Select Category</option>
												@if (!empty($categories))
													@foreach($categories as $value)
														@php
															$lnk=getValue('m_link', $objData);
															if(preg_match('/\//', $lnk ) ){
																$l=explode('/',$lnk);
																$lnk=$l[1];
															}
														@endphp
														<option {{($lnk == $value->cat_alias || old('clink') == $value->cat_alias) ? 'selected' : ''}} value="{{$value->cat_alias}}">{{$value->cat_title}}</option>
													@endforeach
												@endif
											</select>
										</div>
										@php
										$elink = '';
										if (getValue('m_view', $objData) == 'external') {
										  $elink = getValue('m_link', $objData);
										}
										@endphp
										<div class="tab-pane fade {{(getValue('m_view', $objData)=='external')?'active show':''}}" id="external" role="tabpanel" aria-labelledby="external_tab">
											<input type="text" value="{{$elink}}" name="elink" placeholder="http://" class="form-control">
										</div>
									</div>
								</div>
							</div>

						</div>


						<div class="form-group row">

						<label for="m_type" class="col-sm-2 col-form-label">Menu Type <code>*</code></label>
							<div class="col-sm-3">
								<select name="m_type" class="form-control">
									<option value="">Select Type</option>
									@if (!empty($menu_types))
										@foreach($menu_types as $value)
											
											<option {{ getSelected('m_type', $value->mt_id, $objData, '1' ) }} value="{{$value->mt_id}}" > {{$value->mt_title}} </option>
										
										@endforeach
									@endif
								</select>
							</div>


							<label class="col-sm-2 col-form-label">Template <code>*</code></label>
							@php
								$templates = array('default'=>'Default View', 'frontpage'=>'Frontpage View', 'list'=>'List View', 'gallary'=>'Gallary View', 'blog'=>'Blog View');
							@endphp
							<div class="col-sm-3">
								<select name="m_template" class="form-control">
									@foreach($templates as $key=>$value)
										<option {{ getSelected('m_template', $key, $objData, 'default') }} value="{{$key}}">{{$value}}</option>
									@endforeach
								</select>
							</div>							

						</div>						


						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Parent <code>*</code></label>
							<div class="col-sm-3">
								<select name="parent" class="form-control">
									<option value="Root" {{('Root' == getValue('cat_parent',$objData))? 'selected':''}} selected="selected">No Parent (Root)</option>
									@if (!empty($parent_menus))
									@foreach($parent_menus as $value)
									<option {{getSelected('parent', $value->m_id, $objData)}} value="{{$value->m_id}}">{{$value->m_title}}</option>
									@endforeach
									@endif
								</select>
							</div>

							<label class="col-sm-2 col-form-label">Ordering <code>*</code></label>
							<div class="col-sm-3">
								<select name="m_order" class="form-control">
									<option value="">Select Order</option>
									@if (!empty($menu_order))
										@foreach($menu_order as $value)
											<option {{getSelected('m_order', $value->m_order, $objData)}} value="{{$value->m_order}}">{{$value->m_title}}</option>
										@endforeach
									@endif
								</select>
							</div>							

						</div>


					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Assign Category </label>
						<div class="col-sm-6">
							<textarea name="m_showcat" cols="40" rows="10" class="form-control" style="height:80px" spellcheck="false">{{getValue('m_showcat', $objData)}}</textarea>
						</div>

					</div>

					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Menu Status <code>*</code></label>
						<div class="col-sm-6">

								@php
									$status = [ 'active'=>'Active', 'inactive'=>'Inactive' ];
								@endphp
								<select name="m_status" class="form-control">
									@foreach($status as $key => $value)
										<option {{ getSelected('m_status', $key, $objData, 'active') }} value="{{$key}}"> {{$value}} </option>
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
		$(document).ready(function(){
			$( "select[name='clink']" ).change( function(){		
				$("select[name='plink']").val("");
				$("input[name='elink']").val("");
			});
		
			$( "select[name='plink']" ).change( function(){				
				$("select[name='clink']").val("");
				$("input[name='elink']").val("");		
			});	
			
			$( "input[name='elink']" ).change( function(){				
				$("select[name='clink']").val("");
				$("select[name='plink']").val("");
			});	
		});
	</script>
@endpush

