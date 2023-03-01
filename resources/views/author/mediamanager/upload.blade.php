@include('sourcebit::author.layouts.header')
<style type="text/css">
	.alert{ padding:6px 10px; margin-top:10px}
	.alert-danger{display:none;}
	.alert-success{display:none;}
	.drag_drop{width:100%; height: 100px; border: 1px dashed #003bff; vertical-align: middle}
	.drag_drop p{text-align: center;}	
</style>

<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"> {{$title}} </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
	
		<div class="modal-body">

			 <form method="post" action="{{url($pageUrl)}}" id="add_file" enctype="multipart/form-data" >
                    @csrf

					{!! validation_errors($errors) !!}

					<div class="alert alert-danger" role="alert">&nbsp;</div>
					<div class="alert alert-success" role="alert">&nbsp;</div>
			
					<div class="fbody">
					
					<small>Allowed file type jpeg, bmp, png. Maximum file size 1MB. Maximum resolution 1000x1000.</small>

                      <div class="form-group text-center">

					  	<div class="col-sm-12 col-form-label drag_drop mb-4">  
								<p>Drop your files here to upload</p>
						  </div>

					  	<div class="col-sm-12 m-auto">
								
                                <div class="custom-file"> 								 								
                                   <input type="hidden" name="MAX_FILE_SIZE" />
								    <input type="file" id="upload_file" name="file" class="custom-file-input" >
									
                                    <label class="custom-file-label" for="upload_file">Choose file<code> *</code></label>
									
                                </div>
                               
                           </div>
                       </div>


				<div class="form-group row" >
					<label class="col-sm-5 control-label" > </label>
					<div class='col-sm-5'>						
						<input type="submit" value="Upload" class="btn btn-primary" id="submit" />
						&nbsp;
						<button type="button"  data-reload="true" class="btn btn-secondary dismiss" data-dismiss="modal">Close</button>		
					</div>
				</div>

			</from>
		</div>
	
	
	
</div>

@include('sourcebit::author.layouts.footer_script')

<script>
$(function(){


	$("#add_file").on('submit', function(e) {
		e.preventDefault();
		$this = $(this);

		var files = $('#upload_file')[0].files;

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
			  beforeSend: function() {
					$this.find('#submit').before(' <span class="preloader"><i class="fas fa-spinner fa-spin" ></i></span> ');				
           		},
			  success: function(response){
				try{                     
					var jsonObj = $.parseJSON(response);                    
					if (jsonObj.fail == false){
						$this.find('.alert-success').html(jsonObj.messages).hide().slideDown();
						$this.find('.fbody, .preloader').hide();
                    }else{
                        $this.find('.alert-danger').html(jsonObj.messages).hide().slideDown();
						$this.find(".preloader").hide();
                    }

				}catch(e){
					alert('Invalid Json!');
				}
			  },
			  error: function(xhr, textStatus) {
                    alert(xhr.statusText);                    
            	},
		   });

		}else{
		   alert("Please select a file.");
		}

		//return false;
  });
	  
  // preview logo
  $("#upload_file").change(function(){    
        preview(this, '#image_preview');
  });
 

});

</script>


