
@extends("sourcebit::author.master")
@section("content")
<style>
    .data-body .table tr th{
        background-color: #f7f7f7;
        border-color: #dee2e6;
        font-weight: bold;
        color: #444;
    }
</style>
    <section class="content data-body">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{!! $page_icon !!} &nbsp; {{ $title }}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>

                                <button type="button" class="btn btn-tool" >
                                    <a href="{{url($bUrl)}}" class="btn bg-gradient-info btn-sm custom_btn"><i class="mdi mdi-plus"></i> <i class="fa fa-arrow-left"></i> Back </a>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">

                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="18%">Article Alias </th>
                                            <td> {{$objData->title}}</td>
                                        </tr>
                                        <tr>
                                            <th >Article Alias </th>
                                            <td> {{$objData->alias}}</td>
                                        </tr>
                                        <tr>
                                            <th >Category </th>
                                            <td>
                                                @php
                                                    $category_title ='';
                                                @endphp
                                                @if(!empty($objData->categories) && count($objData->categories) >0)
                                                    @foreach($objData->categories as $articleCategory)
                                                        @php
                                                            $category_title .= !empty($articleCategory->cat_title)?'<span class="btn btn-default btn-sm mr-2">'.$articleCategory->cat_title.'</span>':'';
                                                        @endphp
                                                    @endforeach
                                                @endif
                                                {!! $category_title !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th >Featured Image </th>
                                            <td>

                                                @php
                                                    $getFeatureImg = $objData->featuredimg;

                                                        if(!empty($getFeatureImg)):
                                                            $images = explode('|',$getFeatureImg);
                                                            if(!empty($images)):
                                                                foreach($images as $img):
                                                                    echo '<img src="'.$img.'" style="height: 150px; margin: 9px;" class="img-thumbnail" alt="...">';
                                                                endforeach;
                                                            endif;
                                                        endif;
                                                        @endphp


                                            </td>
                                        </tr>
                                        <tr>
                                            <th >Related Articles </th>
                                            <td>
                                                @if (!empty($related_article) && count($related_article) >0)
                                                    @foreach($related_article as $r_article)
                                                        <span class="btn btn-outline-primary btn-sm mr-2">{{$r_article->title}}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th >Tag </th>
                                            <td>
                                                @if (!empty($tags) && count($tags) >0)
                                                    @foreach($tags as $tag)
                                                        <span class="btn btn-outline-secondary btn-sm mr-2">{{$tag->name}}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th >Meta Description </th>
                                            <td> {{$objData->metades}}</td>
                                        </tr>
                                        <tr>
                                            <th >Keywords </th>
                                            <td> {{$objData->metakeys}}</td>
                                        </tr>
                                        <tr>
                                            <th >Canonical URL </th>
                                            <td> {{$objData->canonical}}</td>
                                        </tr>
                                        <tr>
                                            <th >SEO Snippets </th>
                                            <td> {{$objData->seo}}</td>
                                        </tr>
                                        <tr>
                                            <th >Access </th>
                                            <td> 
                                                @if ($objData->access ==2)
                                                    Subscriber
                                                @elseif($objData->access ==3)
                                                    Manager
                                                @elseif($objData->access ==9)
                                                    Admin
                                                @else
                                                    Public
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th >Attributes  </th>
                                            <td>
                                                @php
                                                    $attr = json_decode($objData->attribs, true)??[];
                                                        $attributes = '';
                                                      if(count($objData->categories) >0 && $objData->categories[0]->pivot->r_catlead == 1 ){
                                                            $attributes = '<span class="badge badge-success mr-2"><i  class="fa fa-dot-circle"></i> Lead the Category</span>';
                                                      }
                                                    if(isset($attr['featured']) && $attr['featured'] == 1){
                                                        $attributes .= '<span class="badge badge-success mr-2"><i class="fa fa-star"></i> Featured Posts</span>';
                                                    }
                                                    $suggested ='';
                                                    if(isset($attr['suggested']) && $attr['suggested'] == 1){
                                                        $attributes .= '<span class="badge badge-success mr-2"><i class="fa fa-thumbs-up"></i> Suggested Post</span>';
                                                    }

                                                @endphp
                                                {!! $attributes !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th >Views </th>
                                            <td> {{$objData->hits}}</td>
                                        </tr>
                                        <tr>
                                            <th >Menu </th>
                                            <td>
                                                @php
                                                    $menu = '<span class="badge badge-secondary"><i class="fa fa-times-circle" aria-hidden="true"></i> Menu</span>';
                                                   if ($objData->is_menu ==1) {
                                                       $menu = '<span title="This post is assigned to menu" class="badge badge-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Menu</span>';
                                                   }
                                                @endphp
                                               {!! $menu !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th >Create By </th>
                                            <td> {{ $objData->user->full_name ?? $objData->created_by }}</td>
                                        </tr>
                                        <tr>
                                            <th >Status  </th>
                                            <td> {{$objData->status}}</td>
                                        </tr>

                                        <tr>
                                            <th >Create Date </th>
                                            <td> {{ !empty($objData->created_at)?date('d-m-Y h:i:s A', strtotime($objData->created_at)):''}} </td>
                                        </tr>
                                        <tr>
                                            <th >Update Date </th>
                                            <td> {{ !empty($objData->created_at)?date('d-m-Y h:i:s A', strtotime($objData->updated_at)):''}} </td>
                                        </tr>




                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{$title}}
                        </div>

                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>


    <!-- Default box -->



@endsection

