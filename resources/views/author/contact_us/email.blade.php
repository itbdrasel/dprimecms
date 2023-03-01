
<style type="text/css">
.alert {
  padding: 6px 10px;
  margin-top: 10px
}

.alert-warning {
  display: none;
}

.alert-success {
  display: none;
}

.alert-warning ul {
  margin-bottom: 0px !important;
}

</style>

<form method="post" action="{{ url($bUrl.'/email-send/'.getValue($tableID, $objData) ) }}" id="edit">
  @csrf
  <div class="modal-content">
    <div class="modal-header">
      <input type="hidden" class="datepickerNone">
      <h4 class="modal-title" id="myModalLabel"> {{$title}} </h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
          aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body">
      <div class="card-body">
        <div id="error_message"></div>
        <div class="alert alert-warning" role="alert">&nbsp;</div>
        <div class="alert alert-success" role="alert">&nbsp;</div>
        <div class="col-md-12 fbody">

          <input type="hidden" value="{{ getValue($tableID, $objData) }}" id="id" name="{{$tableID}}">

          <div class="input-group mb-3">
            <label class="col-sm-3 col-form-label" >E-mail <code>*</code></label>
            <input type="email" name="email" value="{{$objData->n_email??''}}" id="email" class="form-control">
          </div>
          <div class="input-group mb-3">
            <label class="col-sm-3 col-form-label">Subject <code>*</code></label>
            <input type="text" name="subject" value="" class="form-control">
          </div>
          <div class="input-group mb-3">
            <label class="col-sm-3 col-form-label">Message </label>
            <textarea name="message" cols="40"  class="form-control" spellcheck="false"></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" id="submit" class="btn btn-primary">Send E-mail</button>&nbsp;&nbsp;
      <button type="button" data-reload="true" class="btn btn-secondary dismiss" data-dismiss="modal">Close</button>
    </div>
  </div>
</form>
<script>
  // $('form#edit').each(function() {
  //   $this = $(this);
  //   $this.find
  // });
  $('#submit').off('click').on('click', function() {
    event.preventDefault();
    var str = $('#edit').serialize();
    $.post('{{ url($bUrl."/email-send/".getValue($tableID, $objData) ) }}', str,
            function(response) {
              if (response == 'success') {
                $('.alert-success').html('successfully send email.').hide().slideDown();
                $('.fbody').hide();
                $('.alert-warning').hide();
              } else {
                var html = '<ul>'
                $.each(response, function(index, item) {
                  html += '<li>' + item + '</li>'
                });
                html += '</ul>'
                $('.alert-warning').html(html).hide().slideDown();
                $('.alert-success').hide();
              }
              return false;
            });


  });

</script>