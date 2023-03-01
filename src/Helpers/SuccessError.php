<?php
namespace Sourcebit\Dprimecms\Helpers;



class SuccessError{


     protected function frontendLayout($pageName, $message,$btn_title,$url){
        $data = [
            'message'       => $message,
            'url_lint'       => $url,
            'btn_title'     => $btn_title,
            'title'         => $btn_title,
        ];
         echo view('frontend.sections.'.$pageName,$data);
     }

    protected function loginLayout($pageName, $message,$btn_title,$url){
        $data = [
            'message'       => $message,
            'url_lint'       => $url,
            'btn_title'     => $btn_title,
            'title'         => $btn_title,
        ];
        echo view($pageName,$data);
    }

    public static function successFrontend($message,$btn_title='',$url=''){

        (new self)->frontendLayout('success',$message,$btn_title,$url);
    }


    public static function errorFrontend($message,$btn_title='',$url=''){
        (new self)->frontendLayout('error',$message,$btn_title,$url);
    }


    public static function successLogin($message,$btn_title='',$url=''){

        (new self)->loginLayout('success',$message,$btn_title,$url);
    }
    public static function errorLogin($message,$btn_title='',$url=''){
        (new self)->loginLayout('error',$message,$btn_title,$url);
    }



}


?>
