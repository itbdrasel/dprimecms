@push('css')
	<style>
		.select2-container--default .select2-selection--multiple .select2-selection__choice{
			color: #000!important;
		}
		.input-group-text{ border-color: #acb1b7 !important; cursor: pointer;}
	</style>
@endpush

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

					<div class="col-md-12">

						{!! validation_errors($errors) !!}

						<input type="hidden"  value="{{ getValue($tableID, $objData) }}" id="id" name="{{ $tableID }}">


						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Article Title <code>*</code></label>
							<div class="col-sm-7">
								<input type="text" value="{{getValue('title', $objData)}}" @if (empty($objData)) id="title"@endif  name="title" class="form-control form-control-lg">

							</div>

						</div>

						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Article Alias <code>*</code></label>
							<div class="col-sm-7">
								<input type="text" value="{{getValue('alias', $objData)}}" name="alias" id="alias" class="form-control">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Category <code>*</code></label>
							<div class="col-sm-7">
								<select name="category[]"  class="select2" multiple="multiple" data-placeholder="Select Category" style="width: 100%;"  >


									@if (!empty($categories))
										@foreach($categories as $key => $category)
											@php
												$selected ='';
                                                $selected = (isset(getValue('category', $objData)[$key]) )? 'selected':'';											
											@endphp

											@if(!empty($objData) && !empty($objData->categories))

												@foreach($objData->categories as $articleCategory)
													@if($articleCategory->cat_id == $category->cat_id)
														@php														
															$selected ='selected';
														@endphp
													@endif
												@endforeach
											@endif
											<option {{$selected}} value="{{$category->cat_id}}">{{$category->cat_title}}</option>
										@endforeach
									@endif
								</select>

							</div>


						</div>


						<div class="form-group row">
							<!-- <label class="col-sm-2 col-form-label">Content <code>*</code></label> -->
							<div class="col-sm-12">
								<textarea name="fulltexts"  class="form-control editor" rows="3" placeholder="Content ..." style="margin-top: 0px; margin-bottom: 0px; height: 172px;">{{getValue('fulltexts',$objData)}}</textarea>
							</div>
						</div>

						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Intro Text </label>
							<div class="col-sm-7">
								<textarea name="introtext" id="introtext" cols="50" rows="10" class="form-control" style="height:100px" spellcheck="false">{{getValue('introtext', $objData)}}</textarea>
								<span id="chars"></span>
							</div>
						</div>

						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Featured Image </label>					
							<div class="col-sm-7">
								<div class="input-group mb-2 mr-sm-2">
								<input type="text" value="{{getValue('featuredimg', $objData)}}" name="featuredimage" id="featuredimage" class="form-control">
									<div class="input-group-prepend">
										<a class="input-group-text" id="featured"><span class="fa fa-image"></span></a> 
									</div>
								</div>

								<span style="position:absolute; top:0%; left:102%; width:250px" class="preview">

								<?php

									$getFeatureImg = getValue('featuredimg', $objData);

									if(!empty($getFeatureImg)):
										$images = explode('|',$getFeatureImg);

										if(!empty($images)):
											foreach($images as $img):
												echo '<span class="m"><span class="x">x</span><img src="'.$img.'"  width="55" height="45" /></span>';
											endforeach;
										endif;
									endif;
								?>

								</span>	
							</div>
							
							
						</div>

						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Related Articles </label>
							<div class="col-sm-7">									
									
								<select name="related_article[]" id="related_article"  class="select2" multiple="multiple" data-placeholder="Select Category" style="width: 100%;"  >

									@if (!empty($relatedArticles))

										@foreach($relatedArticles as $key => $r_article)
											@php
												$selected ='';
                                                $selected = (isset(getValue('related_article', $objData)[$key]) )? 'selected':'';
											@endphp

											@if(!empty($objData) && !empty($objData->related_article))

												@php													
													$articles = json_decode($objData->related_article, true);
													$selected ='';
												@endphp

												@foreach($articles as $key=>$value)
													@if($r_article->id == $value)
														@php
															$selected ='selected';
														@endphp
													@endif
												@endforeach

											@endif
											<option {{$selected}} value="{{$r_article->id}}">{{$r_article->title}}</option>
										@endforeach
									@endif
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Tag </label>
							<div class="col-sm-7">

								<select name="tags[]"  class="select2" multiple="multiple" data-placeholder="Select Tag" style="width: 100%;"  >

									@if (!empty($tags))

										@foreach($tags as $key => $tag)
											@php
												$selected ='';
                                                $selected = (isset(getValue('tags', $objData)[$key]) )? 'selected':'';
											@endphp

											@if(!empty($objData) && !empty($objData->tags))

												@php
													$dbTags = json_decode($objData->tags, true);
													$selected ='';
												@endphp

												@foreach($dbTags as $dbKey=>$dbValue)
													@if($tag->id == $dbValue)
														@php
															$selected ='selected';
														@endphp
													@endif
												@endforeach

											@endif
											<option {{$selected}} value="{{$tag->id}}">{{$tag->name}}</option>
										@endforeach
									@endif
								</select>
							</div>
						</div>


						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Meta Description </label>
							<div class="col-sm-7">
								<textarea name="metades" id="metades" cols="50" rows="10" class="form-control" style="height:100px" spellcheck="false">{{getValue('metades', $objData)}}</textarea>
								<span id="chars"></span>
							</div>
						</div>

						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Keywords </label>
							<div class="col-sm-7">
								<input type="text" value="{{getValue('metakeys', $objData)}}" name="metakeys" class="form-control">
							</div>

						</div>

						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Canonical URL </label>
							<div class="col-sm-7">
								<input type="text" value="{{getValue('canonical', $objData)}}" name="canonical" class="form-control">
							</div>
						</div>

						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">SEO Snippets </label>
							<div class="col-sm-7">
								<textarea name="seo" id="seo" cols="50" rows="10" class="form-control" style="height:100px" spellcheck="false">{{getValue('seo', $objData)}}</textarea>
							</div>
						</div>
						

						<div class="form-group row">


							<label for="" class="col-sm-2 col-form-label">Status <code>*</code></label>
							<div class="col-sm-3">
								<select name="status" class="form-control">
									<option value="">Select Status</option>
									<option {{(getValue('status', $objData)=='active')?'selected':''}} value="active" selected="selected">Active</option>
									<option {{(getValue('status', $objData)=='draft')?'selected':''}} value="draft">Draft</option>
									<option {{(getValue('status', $objData)=='inactive')?'selected':''}} value="inactive">Inactive</option>
								</select>
							</div>

							<label for="" class="col-sm-1 col-form-label">Access <code>*</code></label>
							<div class="col-sm-3">
								<select name="access" class="form-control">
									<option {{(getValue('access', $objData)==1)?'selected':''}}  value="1" selected="selected">Public</option>
									<option {{(getValue('access', $objData)==9)?'selected':''}} value="9">Admin</option>
									<option {{(getValue('access', $objData)==3)?'selected':''}} value="3">Manager</option>
									<option {{(getValue('access', $objData)==2)?'selected':''}} value="2">Subscriber</option>
								</select>
							</div>

						</div>

					</div>
				</div>
				<div class="card-footer">
					<div class="offset-md-3 col-sm-9">
						@php
							$spinner=  '<i class="fas fa-spinner fa-pulse"></i> Please wait... ';
						@endphp
						<button type="submit" name="submit" class="btn btn-success">Save Article</button>
						&nbsp;&nbsp;
						<button name="submit" value="save-n-exit" type="submit"  class="btn btn-primary">Save & Exit</button>
						&nbsp;&nbsp;
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

@push('plugin')
	<script src="{{url('backend/plugins/tinymce/tinymce.min.js')}}"></script>
@endpush
@push('js')
<script>

	$(document).ready(function(){



	});


	$('#title').on('blur', function () {
		getRelatedArticles();
	})
	function getRelatedArticles(){
		let _token   = $('meta[name="csrf-token"]').attr('content');
		var title = $('#title').val();
		$.ajax({
			url: "{{url('author/related-articles')}}",
			type:"POST",
			data:{
				title:title,
				_token: _token
			},
			success:function(response){
				$('#related_article').html(response);
			},
			error: function(error) {
				console.log(error);
			}
		});

	}

</script>
@endpush
