<style type="text/css">
	.alert{ padding:6px 10px; margin-top:10px}
	.alert-warning{display:none;}
	.alert-success{display:none;}
</style>

<div class="modal-content">
	<div class="modal-header">
		<h4 class="modal-title" id="myModalLabel"> {{$title}} </h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	</div>
	<div class="modal-body">
		<form method="post" action="{{url($pageUrl)}}" id="delete" >
			@csrf


			<div class="alert alert-warning" role="alert">&nbsp;</div>
			<div class="alert alert-success" role="alert">&nbsp;</div>

			<div class="fbody">

				<input type="hidden" name="id" id="id" value="{{ $objData->h_id }}" />


				<div class="form-group" >
					<label class="col-sm-7 control-label" >Do you want to delete {{ $objData->h_name }} ? </label>
					<div class='col-sm-5'>

						<input type="submit" value="Yes" class="btn btn-danger" id="submit" />


						&nbsp;<a class="btn btn-default no" data-dismiss="modal" data-reload="false">No</a>
					</div></div>


			</div>
			</from>
	</div>
	<div class="modal-footer">
		<button type="button"  data-reload="true" class="btn btn-secondary dismiss" data-dismiss="modal">Close</button>
	</div>



	<script>
		$(function(){
			$('form#delete').each(function(){
				$this = $(this);
				$this.find('#submit').on('click', function(event){
					event.preventDefault();
					var str = $this.serialize();
					$.post('{{ url($pageUrl) }}', str, function(response){
						var jsonObj = $.parseJSON(response);
						if (jsonObj.fail == false){
							$this.find('.alert-success').html(jsonObj.error_messages).hide().slideDown();
							$this.find('.fbody').hide();
						}else{
							$this.find('.alert-warning').html(jsonObj.error_messages).hide().slideDown();
						}
					});

				});
			});
		});

	</script>


