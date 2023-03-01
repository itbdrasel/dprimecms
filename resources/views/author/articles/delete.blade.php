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

			<input type="hidden" name="id" id="id" value="{{ $objData->id }}" />

			<div class="form-group" >			
			<div class="col-sm-12">
				<p>Title : <strong>{{ $objData->title }}</strong><br>
				Created By : {{ $objData->user->full_name }}<br />
				Created On: {{ date('d M, Y', strtotime($objData->created_at)) }}
				</p>
			</div>

			<div class='col-sm-5'>
				<input type="submit" value="Yes, Delete This" class="btn btn-danger" id="submit" />
				&nbsp;<a class="btn btn-default no" data-dismiss="modal" data-reload="false">No, Go Back</a>

			</div>
		</div>


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


