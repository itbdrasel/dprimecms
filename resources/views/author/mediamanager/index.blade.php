@push('css')

    <style>
        .overlay {           
            position: absolute; background-color: white; top: 0; bottom: 0; left: 0; right: 0; margin: auto; z-index: 10;
        }
        .overlay div{ position: relative; z-index: 10; top:30%; left: 50%;}

        input.form-control.float-left.search_input{
            width: 250px;
        }
        ul.pagination{
            float: right;
        }
        .alert{ padding:6px 10px !important; margin-top:10px}
        .alert-danger{display:none;}
        .alert-success{display:none;} 
        .breadcrumb ul{ padding: 0; margin:0;}
        .breadcrumb li{ display: inline;} 
        
        .drag_drop{width:100%; height: 100px; border: 1px dashed #003bff; vertical-align: middle}
        .drag_drop p{text-align: center;}
        .mediadata .media-action{ display: none;}
        .mediadata .media-action .btn{ padding: 1px 6px}
        .mediadata .card:hover .media-action{ display:inline; position: absolute; top:10px; right: 10px;}
        .mediadata .image{ height: 60px;}
        .mediadata .file-details{ font-size: 14px; text-overflow: clip; }
        .mediadata .files .file-details{padding: 5px 5px!important;}
        .mediadata .file-details .file-name{ height: 20px; overflow: hidden;}
        .mediadata .file-details .file-size{ font-size: 12px;}
        .mediadata .files .file{padding-top: 10px;}
        .mediadata .files .file .fa{ height: 10px;}
        input.form-control.float-left.search_input{width:165px}
        .breadcrump-mediamanager .breadcrumb{ padding: 5px 10px; margin-bottom: .5rem;}

    </style>
@endpush
@extends("sourcebit::author.mediamanager.layout")
@section("content")
<!-- Main content -->

<div class="overlay">
    <div class="spinner-border text-secondary" >
        <span class="sr-only">Loading...</span>
    </div>
</div>

<section class="content data-body">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">

        <div class="row">
        <div class="col-8">
                    <button type="button" class="btn btn-tool" >
                    <a href="{{url($bUrl.'/upload')}}" id="action" data-toggle="modal" data-target="#uploadmodal"  class="btn bg-gradient-info btn-sm custom_btn"> <i class="fa fa-upload"></i> Upload </a>

                    <!-- <a href="{{url($bUrl.'/upload')}}" id="action" data-toggle="modal" data-target="#windowmodal"  class="btn bg-gradient-info btn-sm custom_btn"> <i class="fa fa-upload"></i> Upload </a> -->

                    <a href="{{url($bUrl.'/folder')}}" id="action" data-toggle="modal" data-target="#itemmodal"  class="btn bg-gradient-info btn-sm custom_btn"> <i class="fa fa-folder"></i> Create Folder </a>

                    <a href="{{url($bUrl.'/links')}}"  class="btn bg-gradient-info btn-sm custom_btn"> <i class="fa fa-file"></i> Content Lists </a>
            </div>             

             <div class="col-4">
                  
                        <div class="form-row">
                            <!-- <div class="col-2">
                                @if($path)
                                    <a class="btn btn-default bold" onclick="history.back()"> &larr; </a>
                                @endif                            
                            </div> -->
                            <form action="{{url($bUrl)}}" method="get"  class="form-inline">
                            <div class="col">                            
                                <input type="text" name="filter" value="{{ $filter ?? '' }}" placeholder="Search..." class="form-control float-left search_input"/>

                                <input type="hidden" name="path" value="{{$path}}"/>

                            </div>
                            <div class="col">
                                <input  type="submit" class="btn btn-primary" value="Search"/>
                                &nbsp;<a class="btn btn-default" href="{{ url($bUrl).'?path='.Request::query('path') }}"> Reset </a>
                            </div>
                            </form>
                        </div>
                   

                @if( !empty( Request::query() ) )

                @if( array_key_exists( 'filter', Request::query() ) )

                    Showing results for

                    @if(!empty($filter) )
                        '{{ $filter }}'
                    @endif
                @endif

                @endif

                </div>
        </div>
            
        </div>

        <div class="card-body" id="">

        <div class="col-md-12">

            <div class="row">    

                <div class="col-12 breadcrump-mediamanager">
                       <div class="breadcrumb">{!!$breadcrumb!!}</div>                      
                </div>                

            </div>
        </div>
        </div>


		<div class="col-md-12">
        
        <div class="mediadata">
			<div class="row">
                
                @php                                             
                    if( isset($filter) && !empty($filter) ):
                        $dirContents = collect(Storage::listContents($path))->filter( function($item) use ($filter) {
                            return stripos($item['basename'], $filter) !== false;
                        });
                    else:
                        $dirContents = collect(Storage::listContents($path))->sortBy('type')->toArray();
                    endif;

                    //dd($dirContents);
                                         
                @endphp

                @forelse( $dirContents as $file)                    
                                                                      
                        @if($file['type'] === 'file')
                        <div class="col-sm-1">
                        <div class="card insertable files">
                            <?php 
                                //dd($file);
                            ?>

                            @if($file['extension'] === 'jpg' || $file['extension'] === 'png')
                            
                                <img data-path="{{Storage::url($file['path'])}}" src="{{Storage::url('.tmp/'.base64_encode($file['filename']).'.'.$file['extension']) }}" class="image" title="{{$file['basename']}}" />

                            @elseif($file['extension'] === 'pdf')
                                <a data-path="{{Storage::url($file['path'])}}" class="text-center file" title="{{$file['basename']}}"><i class="fa fa-file-pdf" style="font-size:50px; text-align:center; color:#009ad7"> </i></a>
                            @else($file['extension'] === 'txt')
                                <a data-path="{{Storage::url($file['path'])}}"  class="text-center file" title="{{$file['basename']}}"><i class="fa fa-file-alt" style="font-size:50px; text-align:center; color:#009ad7"> </i></a>
                            @endif

                            <div class="card-body file-details">
                                <p class="card-text mb-0 text-center file-name">{{basename($file['basename'])}}</p>
                                <p class="card-text text-muted text-center file-size">{{round(Storage::size($file['path'])/1024, 2)}} KB</p>
                            </div>

                            <div class="dropdown media-action">
                                <a class="btn btn-default dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="#">View</a>
                                    
                                    <a class="dropdown-item" data-name="{{$file['filename']}}" data-ext="{{$file['extension']}}" data-toggle="modal" data-target="#renamemodal"  >Rename</a>

                                    <a class="dropdown-item" data-name="{{$file['basename']}}" data-toggle="modal" data-target="#deletemodal"  >Delete</a>
                                </div>
                            </div>                            


                        </div> 
                        </div>  
                        @elseif($file['type'] === 'dir' && $file['basename'] !== '.tmp')
                        <div class="col-sm-1">
                        <div class="card directory">   
                            <a data-dir="{{$file['path']}}" href="{{url($bUrl.'?path='.urlencode($file['path']))}}" class="text-center folder"><i class="fa fa-folder" style="font-size:50px; text-align:center; color:#009ad7; padding-top:8px;"> </i></a>

                            <div class="card-body file-details">
                                <p class="card-text mb-0 text-center file-name">{{basename($file['basename'])}}</p>                                
                            </div> 

                            <div class="dropdown media-action">
                                <a class="btn btn-default dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                    <a class="dropdown-item" data-name="{{$file['basename']}}" data-toggle="modal" data-target="#renamemodal"  >Rename</a>

                                    <a class="dropdown-item" data-name="{{$file['basename']}}" data-toggle="modal" data-target="#deletemodal"  >Delete</a>
                                </div>
                            </div> 
                            
                            
                        </div> 
                        </div>                            
                        @endif                                                
                                                        

                    @empty
                    <div class="col"><p> No File Available in this Directory </p></div>
                    @endforelse

                   
                

		 </div><!-- /row -->

         </div><!-- /mediadata -->
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



   <!-- Modal -->
<!--    <div class="modal fade" id="windowmodal" tabindex="-1" role="dialog" aria-labelledby="windowmodal" aria-hidden="true">
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
            </div>
        </div>
    </div> -->

    <!-- Delete Item/Folder Modal -->
   <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="deletemodal" aria-hidden="true">
        <div class="modal-dialog"  role="document">
            <div class="modal-content">
                <div class="modal-header"> Are you sure you want to delete?</div>
                <div class="modal-body">
                
                    <form method="post" class="form" action="{{url($bUrl.'/delete')}}" id="delete" >
                            @csrf
                            {!! validation_errors($errors) !!}

                    <div class="alert alert-danger" role="alert">&nbsp;</div>
                    <div class="alert alert-success" role="alert">&nbsp;</div>
                    
                    <div class="fbody">  

                        <div class="form-group row" >
                        <input type="hidden" name="path" value="{{$path}}">       
                        <input type="hidden" name="name" />
                            <label class="col-sm-8 control-label" > </label>
                            <div class='col-sm-4'>
                                <input type="submit" value="Delete" class="btn btn-primary" id="submit" />			<button type="button"  data-reload="true" class="btn btn-secondary dismiss" data-dismiss="modal">Close</button>		
                            </div>
                        </div>                
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div> 

      <!-- Rename Item/Folder Modal -->
   <div class="modal fade" id="renamemodal" tabindex="-1" role="dialog" aria-labelledby="renamemodal" aria-hidden="true">
        <div class="modal-dialog"  role="document">
            <div class="modal-content">
                <div class="modal-header"> Rename</div>
                <div class="modal-body">
                
                    <form method="post" class="form" action="{{url($bUrl.'/rename')}}" id="rename" >
                            @csrf
                            {!! validation_errors($errors) !!}

                    <div class="alert alert-danger" role="alert">&nbsp;</div>
                    <div class="alert alert-success" role="alert">&nbsp;</div>
                    
                    <div class="fbody">  

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label mb-4">  Item Name </label>

                            <div class="col-sm-7">                                     
                                <input type="hidden" name="path" value="{{$path}}"> 
                                <input type="input" name="name" class="form-control">
                                <input type="hidden" name="oldname" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row" >
                            <label class="col-sm-4 control-label" > </label>
                            <div class='col-sm-5'>
                                <input type="submit" value="Rename" class="btn btn-primary" id="submit" />			<button type="button"  data-reload="true" class="btn btn-secondary dismiss" data-dismiss="modal">Close</button>		
                            </div>
                        </div>                
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div> 


    
   <!-- Create Item/Folder Modal -->
   <div class="modal fade" id="itemmodal" tabindex="-1" role="dialog" aria-labelledby="itemmodal" aria-hidden="true">
        <div class="modal-dialog"  role="document">
            <div class="modal-content">
                <div class="modal-header"> Create New</div>
                <div class="modal-body">
                
                    <form method="post" class="form" action="{{url($bUrl.'/create')}}" id="create_new" >
                            @csrf
                            {!! validation_errors($errors) !!}

                    <div class="alert alert-danger" role="alert">&nbsp;</div>
                    <div class="alert alert-success" role="alert">&nbsp;</div>
                    
                    <div class="fbody">  

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label mb-4">  Item Name </label>

                            <div class="col-sm-7">                                     
                                <input type="hidden" name="path" value="{{$path}}"> 
                                <input type="input" name="item" class="form-control">        
                            </div>
                        </div>

                        <div class="form-group row" >
                            <label class="col-sm-4 control-label" > </label>
                            <div class='col-sm-5'>
                                <input type="submit" value="Create" class="btn btn-primary" id="submit" />			<button type="button"  data-reload="true" class="btn btn-secondary dismiss" data-dismiss="modal">Close</button>		
                            </div>
                        </div>                
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div> 
    

  
  <!-- Create Item/Folder Modal -->
  <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="uploadmodal" aria-hidden="true">
        <div class="modal-dialog"  role="document">
            <div class="modal-content">
                <div class="modal-header"> File Upload</div>
                <div class="modal-body">
                
                <form method="post" action="{{url($bUrl.'/upload')}}" id="add_file" enctype="multipart/form-data" >
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
                          <input type="hidden" name="path" value="{{$path}}"> 
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
						<input type="submit" value="Upload" class="btn btn-primary" id="submit" />						<button type="button"  data-reload="true" class="btn btn-secondary dismiss" data-dismiss="modal">Close</button>		
					</div>
				</div>
			    </from>
                </div>
            </div>
        </div>
    </div>   

        

@endsection

@push('js')

<script src="{{url('backend/js/mediamanager.js')}}"></script>
<script src="{{url('backend/plugins/tinymce/tinymce.min.js')}}"></script>

<script>


$(window).on('load', function(){  
  $( ".overlay" ).fadeOut(100, function() {    
      $( ".overlay" ).remove(); 
  });
});

$(document).on('hidden.bs.modal', function (e) {
    $(this).removeData('bs.modal');
    window.location.reload();
})

 	$(document).ready(function(){
         
        //
        // New Directory
        //
        $("#create_new").on('submit', function(e) {		
            e.preventDefault();
            $this = $(this);
            var formData = new FormData($(this)[0]);
            //console.log(formData);
            $.ajax({
                url: '{{ url($pageUrl."/create") }}',
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
        
        });


    /** *
     * File Upload
    */

    $("#add_file").on('submit', function(e) {
		e.preventDefault();
		$this = $(this);

		var files = $('#upload_file')[0].files;

		var formData = new FormData($(this)[0]);

        $.ajax({
            url: '{{ url($pageUrl."/upload") }}',
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

		//return false;
    });
	  
    // preview logo
    $("#upload_file").change(function(){    
            preview(this, '#image_preview');
    });


    //
    // rename file or directory
    //
    $('#renamemodal').on('show.bs.modal', function(e) {

        var name = $(e.relatedTarget).data('name');
        var ext = $(e.relatedTarget).data('ext');    

        $(e.currentTarget).find('input[name="name"]').val(name); 
        if(ext !== undefined)  $(e.currentTarget).find('input[name="oldname"]').val(name+'.'+ext);
        else $(e.currentTarget).find('input[name="oldname"]').val(name);
    });

    $("#rename").on('submit', function(e) {		
            e.preventDefault();
            $this = $(this);
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ url($pageUrl."/rename") }}',
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
        
        });


    //
    // delete file or directory
    //
    $('#deletemodal').on('show.bs.modal', function(e) {
        var name = $(e.relatedTarget).data('name');
        $(e.currentTarget).find('input[name="name"]').val(name); 
    });

    $("#delete").on('submit', function(e) {		
        e.preventDefault();
        $this = $(this);
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: '{{ url($pageUrl."/delete") }}',
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

    });  
    
    
        //media manager to editor	
		$('.insertable img, .insertable a').on("dblclick", function(e) {
            e.preventDefault();
			var path =  $(this).data('path');
             window.parent.postMessage({
                mceAction: 'mceGeturl',
                url: path
            }, '*');
    
            //window.opener.postMessage('abcd', '*');
            
            //set value in the filed from window.opner popup
           if (window.opener != null && !window.opener.closed) { 
                field = window.opener.setValue(path);       
                window.close();
           }

		});

	

    }); /* Jquery end */


</script>

@endpush
