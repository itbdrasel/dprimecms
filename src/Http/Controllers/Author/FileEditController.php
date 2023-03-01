<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileEditController extends Controller
{

    private $data;
    private $bUrl;
    public function __construct(){
        $this->bUrl = 'author/file-edit/';
    }


    public function layout($pageName){

        $this->data['bUrl']             = $this->bUrl;
        $this->data['page_icon']        = '<i class="fa fa-edit"></i>';
        echo view('sourcebit::author.file_edit.'.$pageName.'', $this->data);
    }

    public function sitemap(){
        
        if (!File::exists( public_path(__FUNCTION__.'.xml'))) {
           File::put(public_path(__FUNCTION__.'.xml'),'');
        }

        $this->data['title']        = __FUNCTION__.' Edit';
        $this->data['name']         = 'Sitemap';
        $this->data['pageUrl']      = $this->bUrl.__FUNCTION__;
        $this->data['fileName']     = pathinfo(public_path(__FUNCTION__.'.xml'), PATHINFO_BASENAME);
        $this->data['fileRead']     = File::get(public_path(__FUNCTION__.'.xml'));

        $this->layout('index');
    }

    public function store(Request $request)
    {
        $files = ['sitemap.xml', 'robots.txt'];
        $getFileName = $request['fileName'];

        if(in_array($getFileName, $files)){
            File::put(public_path($getFileName),  $request['content']);
        }
        return redirect()->back()->with('success', 'Successfully Updated');
    }


    public function robots(){

        if (!File::exists( public_path(__FUNCTION__.'.txt'))) {
            File::put(public_path(__FUNCTION__.'.txt'),'');
        }

        $this->data['title']        = __FUNCTION__.' Edit';
        $this->data['name']         = 'Robots';
        $this->data['pageUrl']      = $this->bUrl.__FUNCTION__;
        $this->data['fileName']     = pathinfo(public_path(__FUNCTION__.'.txt'), PATHINFO_BASENAME);
        $this->data['fileRead']     = File::get(public_path(__FUNCTION__.'.txt'));

        $this->layout('index');
    }





}
