<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;


use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Sourcebit\Dprimecms\Models\Article;
use Sourcebit\Dprimecms\Models\Menu;
use Illuminate\Support\Str;
use Session;
use Illuminate\Http\Request;
use Validator;
use DB;

class AjaxJsonController extends Controller
{
    public function __construct(){

    }

    public function getAlias(Request $request){
        $title = $request['title'];
        return clean_url($title);
    }

    public function orderBy(Request $request){
        $id = $request['id'];
        $change_id = $request['change_id'];
        $change_data = Menu::select('m_order')->where('m_id',$change_id)->first();
        $data = Menu::select('m_order')->where('m_id',$id)->first();
        Menu::where('m_id',$id)->update(['m_order'=>$change_data->m_order]);
        Menu::where('m_id',$change_id)->update(['m_order'=>$data->m_order]);
    }


    public function relatedArticles(Request $request){
        $titles = trim($request['title']);
        $titles = explode(" ",$titles);
        $html='';
        if (!empty($titles)){
            $query = Article::orderBy('title');
            foreach ($titles as $key=>$value){
                $query->orWhere('title', 'like',  '%'.$value.'%');

            }
            $data =   $query->get();
            if (!empty($data)) {
                foreach ($data as $key=>$value){
                    $html .='<option  value="'.$value->id.'">'.$value->title.'</option>';
                }

            }
        }
        return $html;
    }

}
