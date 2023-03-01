@include('sourcebit::author.layouts.header')
<style type="text/css">
	.alert{ padding:6px 10px; margin-top:10px}
	.alert-danger{display:none;}
	.alert-success{display:none;}
</style>

<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"> {{$title}} </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
	
		<div class="modal-body">

			 <form method="post" action="{{url($bUrl.'/logo/')}}" id="add_image" enctype="multipart/form-data" >
                    @csrf

					{!! validation_errors($errors) !!}

					<div class="alert alert-danger" role="alert">&nbsp;</div>
					<div class="alert alert-success" role="alert">&nbsp;</div>
			
					<div class="fbody">
					


                      <div class="form-group text-center">

					  	<label for="h_images" class="col-sm-3 col-form-label"> <img src="{{url($objData->s_value)}}" id="image_preview" class="img_search img-thumbnail" alt=""> </label>

					  	<div class="col-sm-6 m-auto">
								
                                <div class="custom-file"> 								 								
                                   <input type="hidden" name="MAX_FILE_SIZE" />
								    <input type="file" id="image_file" name="logo" class="custom-file-input" >
									
                                    <label class="custom-file-label" for="image_file">Choose file<code> *</code></label>
									<small>Allowed file type jpeg, bmp, png. Maximum file size 1MB. Maximum resolution 1000x1000.</small>
                                </div>
                               
                           </div>
                       </div>


			<div class="form-group row" >
				<label class="col-sm-5 control-label" > </label>
				<div class='col-sm-5'>
					<input type="submit" value="Upload Image" class="btn btn-primary" id="submit" />								
				</div>
		   </div>


		</div>
	</from>
	
	
	
</div>

<div class="modal-footer">
	<button type="button"  data-reload="true" class="btn btn-secondary dismiss" data-dismiss="modal">Close</button>
</div>


@include('sourcebit::author.layouts.footer_script')

<script>
$(function(){

	$("#add_image").on('submit', function(e) {
		e.preventDefault();
		$this = $(this);
		var files = $('#image_file')[0].files;

		var formData = new FormData($(this)[0]);

		// Check file selected or not
		if(files.length > 0 ){
		   $.ajax({
			  url: '{{ url($pageUrl) }}',
			  type: 'post',
			  data: formData,
			  cache: false,
			  contentType: false,
			  processData: false,
			  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			  success: function(response){

				 if(response != 0){

					var jsonObj = $.parseJSON(response);

					if (jsonObj.fail == false){
						$this.find('.alert-success').html(jsonObj.messages).hide().slideDown();
						$this.find('.fbody').hide();
					}else{
						$this.find('.alert-danger').html(jsonObj.messages).hide().slideDown();
					}

				 }else{
					alert('file not uploaded');
				 }
			  },
		   });

		}else{
		   alert("Please select a file.");
		}

		//return false;
  });
	  
  // preview logo
  $("#image_file").change(function(){    
        preview(this, '#image_preview');
  });


});

</script>


