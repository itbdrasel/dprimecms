
@extends("sourcebit::author.master")
@section("content")
    <section class="content guest-profile-area">
        <!-- Default box -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"> {!! $page_icon !!} &nbsp; {{ $title }} </h2>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
{{--                            <button type="button" class="btn btn-tool" >--}}
{{--                                <a href="{{url('system/core/users')}}" class="btn btn-info btn-sm"><i class="mdi mdi-plus"></i> <i class="fa fa-arrow-left"></i> Back</a>--}}
{{--                            </button>--}}


                        </div>
                    </div>
                    <div class="card-body frontoffice-body">
                        <div class="col-10">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="guest-profile-box">
                                        <div class="guest-profile-pic">
                                            <img src="{{url('/backend/images')}}/default-avatar.png">
                                        </div>
                                        <div class="text-center">
                                            <h4>{{$objData->full_name}}</h4>
                                            <span>User Role : {{ $objData->name}}</span>
                                        </div>
                                    </div>

                                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        @if($permission =='permission')
                                        <a class="nav-link active" id="pills-permission-tab" data-toggle="pill" href="#v-pills-permission" role="tab" aria-controls="v-pills-permission" aria-selected="false">Permissions</a>
                                        @else
                                            <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="true">User Information</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade @if($permission !='permission') show active @endif" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                            <div class="guest_info mb-4">
                                                <h2>User Information</h2>
                                                <div class="row p-2">
                                                    <div class="col-md-6 mb-2">
                                                        <p class="label">Name</p>
                                                        <p>{{$objData->full_name}}</p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <p class="label">Email</p>
                                                        <p>{{$objData->email}}</p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <p class="label">Last Login</p>
                                                        <p>{{$objData->last_login}}</p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <p class="label">User Role</p>
                                                        <p>{{$objData->name}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade  @if($permission =='permission') show active @endif" id="v-pills-permission" role="tabpanel" aria-labelledby="v-pills-permission-tab">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="15%" >Section Name</th>
                                                    <th>Permissions</th>
                                                </tr>
                                                @php
                                                    $rolePermissions = dAuth()->findRoleById($objData->role_id);
                                                @endphp
                                                @if( $sectionNames->count() > 0)

                                                    @foreach($sectionNames as $sectionName)

                                                        @php
                                                            $sectionPermission = json_decode($sectionName->section_roles_permission);
                                                            //see($sectionPermission);
                                                        @endphp

                                                        @if(in_array($objData->role_id, $sectionPermission) )

                                                            <tr>

                                                                <td>{{ $sectionName->section_name }} </td>

                                                                @php

                                                                    $actions = json_decode($sectionName->section_action_route);
                                                                    $permission = json_decode($objData->permissions, true);
                                                                    $sl =0;

                                                                @endphp

                                                                <td>
                                                                    <div class="row">
                                                                    @foreach($actions as $key => $value)

                                                                        @php
                                                                            $sl++;
                                                                                $checked = '';

                                                                        @endphp

                                                                        @if(in_array($objData->role_id, $value))

                                                                            <!-- check whether permission exist and is it set true  -->

                                                                                @if($rolePermissions->permissions)

                                                                                    @if(array_key_exists($key, $rolePermissions->permissions))
                                                                                        @php
                                                                                            $checked = $rolePermissions->permissions[$key] ? "checked" : "";
                                                                                        @endphp
                                                                                    @endif
                                                                                @endif
                                                                                @if(!empty($permission) && array_key_exists($key, $permission))
                                                                                    @php
                                                                                        $checked = $permission[$key]?'checked':'';
                                                                                    @endphp
                                                                                @endif


                                                                                <div class="col-4 mb-2">
                                                                                    @if ($objData->id != dAuth()->getUser()->id)
                                                                                        <input id="permission_{{$sl}}" type="checkbox" class="role-permission" {{$checked}} data-page="{{$key}}" data-action="{{$key}}" data-id="{{$objData->id}}">
                                                                                    @else
                                                                                        @if($checked =='checked')
                                                                                            <i style="color: #0075ff" class="fas fa-check-square"></i>
                                                                                        @else
                                                                                            <i style="color: red"  class="fas fa-window-close"></i>
                                                                                        @endif
                                                                                    @endif
                                                                                    @php
                                                                                        $actionName = substr($key, strrpos($key, '.')+1 );
                                                                                    @endphp

                                                                                    <label for="permission_{{$sl}}" class="form-check-label">{{ ucfirst(str_replace('_',' ',$actionName))  }}</label>&nbsp;
                                                                                </div>


                                                                            @endif

                                                                        @endforeach
                                                                    </div>
                                                                </td>

                                                            </tr>

                                                        @endif



                                                    @endforeach
                                                @endif
                                            </table>
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{$title}}
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
@push('js')
    <script>
        $(document).ready(function(){
            $(document).on('click', '.role-permission', function(){
                var id = $(this).attr('data-id');
                var action = $(this).attr('data-action');
                var val = 0;
                if($(this).is(':checked')){ val = 1; }
                $.ajax({
                    type: "post",
                    url: "{{ url('author/user_permission') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                        action: action,
                        value: val
                    },
                    success: function(){
                        console.log('Assigned - ' + action + ' = ' + val + ' - to ' + id);
                    },
                    error: function(err){
                        console.log(err);
                    }
                });
            });
        });
    </script>
@endpush
@push('css')
    <style>
        table.blueTable {
            margin: 10px;
            border: 1px solid #1C6EA4;
            text-align: left;
        }
        table.blueTable td, table.blueTable th {
            border: 1px solid #AAAAAA;
            padding: 5px 5px;
        }

    </style>
@endpush
