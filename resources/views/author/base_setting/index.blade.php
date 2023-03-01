@push('css')
    <style type="text/css">    
        #settings .tab-content{ padding: 20px 10px;}
        #settings .nav-tabs .nav-link{ background: none; font-weight: bold; color: #666; outline: none;}
        #settings .nav-tabs .nav-link:hover{ border-color: white; border-bottom: 1px solid #1e2e8b;}
        #settings .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
            border: none;
            border-bottom: 1px solid #1e2e8b;
            color: #1e2e8b !important;        
        }
    </style>

@endpush

@extends("sourcebit::author.master")
@section("content")
    <!-- Main content -->

    <section class="content">
        <form method="post" action="{{url($bUrl.'/store')}}" enctype="multipart/form-data" >
            @csrf
        <!-- Default box -->
        <div class="card">

            <div class="card-body" id="settings">
        
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="settingsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-toggle="tab" data-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true"> General </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="true"> Contact Information </button>
        </li>  

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="meta-tab" data-toggle="tab" data-target="#meta" type="button" role="tab" aria-controls="meta" aria-selected="false">Meta Settings</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="social-tab" data-toggle="tab" data-target="#social" type="button" role="tab" aria-controls="social" aria-selected="false">Social Networks</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="logo-tab" data-toggle="tab" data-target="#logo" type="button" role="tab" aria-controls="logo" aria-selected="false">Logo Upload</button>
        </li>
        </ul>


<!-- Tab panes -->
<div class="tab-content">

<div class="col-sm-8"> {!! validation_errors($errors) !!}</div>

  <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                
                <div class="col-sm-9">
                        <div class="form-group row">
                            <label  class="col-sm-3 col-form-label">App Name <code>*</code></label>
                            <div class="col-sm-8">
                                <input type="text" value="{{config('settings')['appName']}}" name="app_name"class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-9">
                    <div class="form-group row">
                        <label  class="col-sm-3 col-form-label">App URL <code>*</code></label>
                        <div class="col-sm-8">
                            <input type="text" value="{{config('settings')['url']}}" name="app_url" class="form-control">
                        </div>
                    </div>
                </div>
   
    
                

                <div class="col-sm-9">
                    <div class="form-group row">
                        <label  class="col-sm-3 col-form-label">Currency Symbol <code>*</code></label>
                        <div class="col-sm-8">
                            <select class="form-control"  name="currency_symbol">
                                <option {{config('settings')['c_symbol']=='BDT'?'selected':''}} value="BDT">BDT</option>
                                <option {{config('settings')['c_symbol']=='TK'?'selected':''}} value="TK">TK</option>
                                <option {{config('settings')['c_symbol']=='USD'?'selected':''}} value="USD">USD</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group row">
                        <label  class="col-sm-3 col-form-label">Currency Order <code>*</code></label>
                        <div class="col-sm-8">
                            <select class="form-control"  name="currency_order">
                                <option {{config('settings')['c_order']=='left'?'selected':''}} value="left">left</option>
                                <option {{config('settings')['c_order']=='right'?'selected':''}} value="right">right</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group row">
                        <label  class="col-sm-3 col-form-label">Date Format <code>*</code></label>
                        <div class="col-sm-8">
                            <select class="form-control"  name="date_format">
                                <option {{config('settings')['date_format']=='13/12/2017'?'selected':''}} value="13/12/2017">13/12/2017</option>
                                <option {{config('settings')['date_format']=='2017-12-13'?'selected':''}} value="2017-12-13">2017-12-13</option>
                                <option {{config('settings')['date_format']=='13-12-2017'?'selected':''}} value="13-12-2017">13-12-2017</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group row">
                        <label  class="col-sm-3 col-form-label">Analytics <code>*</code></label>
                        <div class="col-sm-8">
                            <input type="text" value="{{config('settings')['analytics']}}" name="analytics" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-sm-9">
                    <div class="form-group row">
                        <label  class="col-sm-3 col-form-label">Language <code>*</code></label>
                        <div class="col-sm-8">
                            <input type="text" value="{{config('settings')['language']}}" name="language" class="form-control">
                        </div>
                    </div>
                </div>
              <div class="col-sm-9">
                  <div class="form-group row">
                      <label  class="col-sm-3 col-form-label">Feed Activate</label>
                      <div class="col-sm-8">
                          <select class="form-control"  name="feedActivate">
                              <option {{config('settings')['feedActivate']==1?'selected':''}} value="1">Yes</option>
                              <option {{config('settings')['feedActivate']!=1?'selected':''}} value="">No</option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="col-sm-9">
                  <div class="form-group row">
                      <label  class="col-sm-3 col-form-label">Feed Limit</label>
                      <div class="col-sm-8">
                          <input type="text" value="{{config('settings')['feedLimit']}}" name="feedLimit" class="form-control">
                      </div>
                  </div>
              </div>

                

    </div>


    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="col-sm-9">
                    <div class="form-group row">
                        <label  class="col-sm-3 col-form-label">E-mail <code>*</code></label>
                        <div class="col-sm-9">
                            <input type="text" value="{{config('settings')['email']}}" name="email" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group row">
                        <label  class="col-sm-3 col-form-label">App Address <code>*</code></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="app_address" >{{config('settings')['appAddress']}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group row">
                        <label  class="col-sm-3 col-form-label">Contact <code>*</code></label>
                        <div class="col-sm-9">
                            <input type="text" value="{{config('settings')['contact']}}" name="contact" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-sm-9">
                    <div class="form-group row">
                        <label  class="col-sm-3 col-form-label">Map Code/Link</label>
                        <div class="col-sm-9">
                            <input type="text" value="{{config('settings')['mapCode']}}" name="mapCode" class="form-control">
                        </div>
                    </div>
                </div>


    </div>


  <div class="tab-pane fade" id="meta" role="tabpanel" aria-labelledby="meta-tab">

        <div class="col-sm-9">
            <div class="form-group row">
                <label  class="col-sm-3 col-form-label">App Title <code>*</code></label>
                <div class="col-sm-9">
                    <input type="text" value="{{config('settings')['appTitle']}}" name="app_title" class="form-control">
                </div>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="form-group row">
                <label  class="col-sm-3 col-form-label">App Description <code>*</code></label>
                <div class="col-sm-9">
                    <textarea class="form-control" rows="3" name="description" >{{config('settings')['description']}}</textarea>
                </div>
            </div>
        </div> 


  </div>


  <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
      <div class="col-sm-8" id="social_aria">

          @php
          $social_all = json_decode($social);
          @endphp
          <div class="form-group row">
              <label  class="col-sm-3 col-form-label">Network Name </label>
              <div class="col-sm-3">
                  <input type="text" placeholder="Network Name" value="{{$social_all[0]->network_name??''}}" name="network_name[]" class="form-control">
              </div>
              <div class="col-sm-4">
                  <input type="text" value="{{$social_all[0]->network_link??''}}" placeholder="Network Link" name="network_link[]" class="form-control">
              </div>
              <div class="col-sm-1">
                  <a style="cursor: pointer" class="input_add btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
              </div>
          </div>
          @if (!empty($social_all))
              @php
              $sl =0;
              @endphp
              @foreach($social_all as $key=>$value )
                  @if($sl++ >0)
          <div class="form-group row" id="remove_div_{{$sl}}">
              <label  class="col-sm-3 col-form-label"></label>
              <div class="col-sm-3">
                  <input type="text" placeholder="Network Name" value="{{$value->network_name??''}}" name="network_name[]" class="form-control">
              </div>
              <div class="col-sm-4">
                  <input type="text" value="{{$value->network_link??''}}" placeholder="Network Link" name="network_link[]" class="form-control">
              </div>
              <div class="col-sm-1">
                  <a style="cursor: pointer" onclick="removeDiv({{$sl}})"  class="input_remove btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></a>
              </div>
          </div>
                  @endif
              @endforeach
          @endif
      </div>
  </div>


  <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
    
    <div class="col-sm-9">
        <div class="form-group row">
            <label  class="col-sm-3 col-form-label">App Logo</label>
            <div class="col-sm-2">
            <img class="img-thumbnail" height="75" src="{{config('settings')['logo']}}" />
            </div>
            <div class="col-sm-4">
                <a id="action" style="margin-top: 25px" data-toggle="modal" class="btn btn-primary" data-target="#windowmodal" href="{{url($bUrl.'/logo/')}}"> Upload Logo</a>
            </div>
        </div>
    </div>



  </div>


</div>      








    
            <!-- /.card-body -->
            <div class="card-footer">
                <div class="offset-md-1 col-sm-9">
                    <button type="submit" class="btn btn-primary">Save</button>&nbsp;&nbsp;
                    <a href="{{url($bUrl)}}"  class="btn btn-warning">Cancel</a>
                </div>
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->
        </form>
    </section>
    <!-- /.content -->
    <!-- Modal -->
    <div class="modal fade" id="windowmodal" tabindex="-1" role="dialog" aria-labelledby="windowmodal" aria-hidden="true">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('js')
<script>
$(document).ready(function(){

    $('#settingsTab button').click(function(e) {
        e.preventDefault();
        $(this).tab('show');                        
    });
    
    $("#settingsTab button[data-toggle='tab']").on("shown.bs.tab", function(e) { 
        
        if($(e.target).attr('id') === 'logo-tab') $('.card-footer').hide();
        else $('.card-footer').show();
        localStorage.setItem('activeTab', $(e.target).attr('data-target'));
    });

    var activeTab = localStorage.getItem('activeTab');          

    if (activeTab) {
        $('#settingsTab button[data-target="'+activeTab+'"]').tab('show');
    }
});
</script>


<script type="text/javascript">
    count = {{count($social_all??[])}};
    $('.input_add').on('click', function () {
        var html ='' +
            '<div class="form-group row" id="remove_div_'+count+'">' +
                '<label  class="col-sm-3 col-form-label"></label>' +
                '<div class="col-sm-3">' +
                    '<input type="text" placeholder="Network Name" value="" name="network_name[]" class="form-control">' +
                '</div>' +
                '<div class="col-sm-4">' +
                    ' <input type="text" value="" placeholder="Network Link" name="network_link[]" class="form-control">' +
                '</div>' +
                '<div class="col-sm-1">' +
                    '<a style="cursor: pointer" onclick="removeDiv('+count+')"  class="input_remove btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></a>' +
                '</div>' +
            '</div>';
        $('#social_aria').append(html);
        count++;
    })
    function removeDiv(id) {
        $('#remove_div_'+id).remove();
    }
</script>
@endpush


