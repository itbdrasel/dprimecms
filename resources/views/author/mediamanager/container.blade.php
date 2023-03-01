@push('css')

    <style>

    </style>
@endpush
@extends("sourcebit::author.master")
@section("content")
<!-- Main content -->
<section class="content data-body">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <!-- <a href="{{url($bUrl)}}" id="mm-modal" data-toggle="modal" data-target="#mm"  class="btn bg-gradient-info btn-sm custom_btn"> <i class="fa fa-upload"></i> Upload </a> -->

            <button type="button" class="btn btn-primary" id="load">
                Launch 
            </button>
        </div>

        <div class="col-md-12">

            <div class="row media-container">
                 <!-- <div class="spinner-border"></div> -->
            </div>
        </div>



		<div class="col-md-12 mt-4">
        
			<div class="row">
                
		    </div><!-- /row -->
	  </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            
        </div>
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

    
   <!-- Create Item/Folder Modal -->
   <div class="modal fade" id="mm" tabindex="-1" role="dialog" aria-labelledby="mm" aria-hidden="true">
        <div class="modal-dialog modal-lg"  role="document">
            <div class="modal-content">
                <div class="modal-header"> Create New</div>
                <div class="modal-body">
                    <h1>here</h1>
                   
                </div>

            </div>
        </div>
    </div>

@endsection

@push('js')

<script>

/* if ($('#mm-modal').length ) {
    
	$('.modal').on('shown.bs.modal', function (e) {       
		var target = $(e.relatedTarget);

        //alert(target[0].href);

		$.ajax({
			url: target[0].href,
			success: function(response)
			{
				$('#mm .modal-content').html(response);
				$('#mm .modal-title').html('<input type="hidden" id="modal_hidden" value="1">');
			}
		}); 

		//$(this).find('.modal-content').load(target[0].href);
	});


	$(".modal").on("hide.bs.modal", function (e) {
		if ($('.dismiss').data('reload')) {
			location.reload();
		} else {
			$(".modal-body").html("");
		}

	});
}   */  

$(document).on("click", ".media-container .mediadata a", function(e) {
    e.preventDefault();
    //$('.media-container').load(this.href);

    //window.open(this.href,"_self","");
    
});

$(document).ready(function(){     
    
    //$('.media-container').load('{{url($bUrl)}}');

    $('#load').on('click', function(){
        window.open('{{url($bUrl)}}',"","height=500,width=900,scrollbars=no,toolbar=no,menubar=no,top=100,left=200");
    });
    
});


</script>

@endpush
