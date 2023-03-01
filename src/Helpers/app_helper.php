<?php

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


/************
 * **
 * App Helper
 * Version 1.3
 * @author - Mainul Islam
 * Basic functions for the App
 * **
 */



/* * *
 * Get the available Auth instance.
 *
 * @return App\Repositories\AuthInterface
 */

 function dAuth(){
    return app('Sourcebit\Dprimecms\Repositories\AuthInterface');
 }

/*****
 * validation_errors()
 * formating validation errors for views
 **/

function validation_errors($errors){
    if($errors->any()){
        echo '<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><ul>';
        foreach ($errors->all() as $error){
            echo '<li>'.$error.'</li>';
        }
        echo '</ul></div>';
    }
}


/*****
 * home()
 * Return true if it is homepage
 */

function home(){
    $getSegment = segment(1);

    if($getSegment ) return false; //no uri segment means it is homepage
    else return true;
}


/* * *
 * successPage()
 * @param Asoc Array - $title, $message, $actionUrl, $actionText
 *
 */
function successPage($data = []){
    echo view('frontend.layouts.success_page', $data);
    exit;
}



/*****
 * emailSender()
 * @param $viewData['tempalteName'=> , 'templateData' => '']
 * @param $mailData ['to', 'toName', 'subject', 'from', 'fromName']
 */
function emailSender($viewData, $mailData){
    Mail::send($viewData['templateName'], $viewData['templateData'], function($message) use($mailData) {
        $message->to($mailData['to'], $mailData['toName'])->subject($mailData['subject']);
        $message->from($mailData['from'], $mailData['fromName']);
    });

}




/*****
 * isAlphaNumeric()
 * form validation callback.
 **/

function isAlphaNumeric($attribute, $value, $fail )
{
    if(preg_match('/[A-Za-z_]/', $value) && preg_match('/[0-9]/', $value))
        return true;
    else
        $fail(ucfirst($attribute).' must be the combination of letters and numbers.');
}


/*****
 * reCAPTCHA validation Check
 * recaptcha()
 **/

function recaptcha($attribute, $value, $fail){
    $secret = '6LcyyqMZAAAAAGLr8ltlPQjN1gZJSz3AcmAZw0Gg';
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$value."&remoteip=".$_SERVER['REMOTE_ADDR']);
    $obj = json_decode($response);

    if($obj->success == true){
        return true;
    }else{
        $fail('Only the human can change the password!');
    }
}


/*****
 * segment()
 * Return uri segment. return default if not get.
 */

function segment($segment, $default = '', $slash = ''){

    $getSegment = trim(Request::segment($segment));

    if(!empty($getSegment))
        return $getSegment.$slash;
    else
        return $default.$slash;
}



/*****
 * clean_url()
 * return url-friendly string. unicode suported.
 */

function clean_url($str, $separator = '-', $lowercase = false){
    $q_separator = preg_quote($separator, '#');

    // 1. Remove all special characters except Unicode characters
    // 2. Remove all space to separator.

    $trans = [
        '[^a-zA-Z0-9\p{L}\p{M}\p{N}\s\-]' => '',
        '\s+' => $separator,
        '(' . $q_separator . ')+' => $separator,
    ];

    $str = strip_tags($str);
    foreach ($trans as $key => $val){
        $str = preg_replace('/' . $key . '/iu', $val, $str);
    }

    if ($lowercase === true) $str = mb_strtolower($str);

    return trim(trim($str, $separator));
}




/*****
 * params()
 * Return url query with the leading separators.
 */

function params($lead = ''){

    //$fullUrl = Request::fullUrl();
    //$parsedUrl = parse_url($fullUrl);
    //$param = $parsedUrl['query'] ?? ''; //http_build_query(Request::query());

    $param = http_build_query(Request::query());

    if(!empty($param)) return ($lead ?: '?').$param;
    else return NULL;

}

/***
 * return specific words
 * @param string $text - Text Content
 * @param int $length - Text Lenght Number
 *
 * @return str;
 **/
function text_num($text, $length){
    if(strlen($text) > $length) $text = substr($text, 0, strpos($text, ' ', $length));
    return $text;
}


/*****
 * getOrder()
 * sorting table fields by asc or desc
 ***
 * @$fields must be indexed array.
 * @$default must need a db table field name.
 **/

function getOrder($fields, $default){

    $getOrder = [];
    $order = Request::get('order') ?? 2; // OrderStatus asc or desc;
    $by = Request::get('by'); // by field name;

    //define ASC or DESC
    if($order == 1) $getOrder['order'] = "ASC";
    elseif( $order == 2) $getOrder['order'] = "DESC";
    else $getOrder['order'] = "DESC";

    // define order by which field
    if(!empty($by) ){
        if(array_key_exists($by, $fields)){
            $getOrder['by'] = $fields[$by];
        }else $getOrder['by'] = $default;

    }else{
        $getOrder['by'] = $default;
    }
    return $getOrder;
}


/* * * *
 * Define selected item in option list
 * @param $fieldName - input field name
 * @param $data - query data
 * @param $default - consider default value if query value is empty.
 *
 * @return String
 **/

function getValue($field, $data, $default = NULL){

    $currentValue = ( !empty($data) && !empty($data->$field) ) ? $data->$field : old($field, $default);

    if( !empty($currentValue) ) return $currentValue;
    else return $default;

}

/* * * *
 * Define selected item in option list
 * @param $fieldName - input field name
 * @param $optionValue - input list option value
 * @param $data - query data
 * @param $default - consider default value if query value is empty.
 *
 * @return String
 **/

function getSelected($fieldName, $optionValue, $data, $default = NULL ){

    if( $optionValue == getValue($fieldName, $data) ) return 'selected';
    elseif($optionValue == $default) return 'selected';

}


/* * * *
 * Take the model instance and retun post/article
 * url based on configuration (id/alias, alias/id, id-alias, alias-id).
 *
 * @param Model $postArticle
 *
 * @return String
 **/

function postUrl($alias, $id){
    $postUrl = '';

    $urlPattern = config('settings.urlPattern');

    switch($urlPattern){
        case('id/alias'):
            $postUrl = url($id.'/'.$alias);
            break;
        case('alias/id'):
            $postUrl = url($alias.'/'.$id);
            break;
        case('id-alias'):
            $postUrl = url($id.'-'.$alias);
            break;
        case('alias-id'):
            $postUrl = url($alias.'-'.$id);
            break;
        default:
            $postUrl = url('a/'.$alias);
    }

    return $postUrl;
}

/* * * *
* Get post Alias from url based on Configuration
* @param Model $postArticle
*
* @return String
**/
function getAlias(){

    $alias = '';
    $urlPattern = config('settings.urlPattern');

    $segmentOne = Request::segment(1);
    $segmentTwo = Request::segment(2);

    $segments = explode('-', $segmentOne);
    $totalSects = count($segments);
    $isMenu = FALSE;

    // checking alias is article alias or menu alias
    // article alias last part numeric( abcd-12 )
    if($urlPattern === 'id-alias'){
        if($totalSects > 1){
            if( !is_numeric($segments[0]) ) $isMenu = TRUE;
        }else $isMenu = TRUE;

    }elseif($urlPattern === 'alias-id'){
        if($totalSects > 1){
            if( !is_numeric($segments[$totalSects-1]) ) $isMenu = TRUE;
        }else $isMenu = TRUE;
    }

    switch($urlPattern){
        case('id/alias'):

            if($segmentTwo) $alias = $segmentTwo; //article alias
            else $alias = $segmentOne; // menu alias

            break;
        case('alias/id'):
            $alias = $segmentOne;
            break;
        case('id-alias'):

            if($isMenu) $alias = $segmentOne; //menu alias
            else $alias = substr($segmentOne, strpos($segmentOne, '-')+1, strlen($segmentOne)); //article alias

            break;
        case('alias-id'):

            if($isMenu) $alias = $segmentOne; //menu alias
            else $alias = substr($segmentOne, 0, strrpos($segmentOne, '-')); //article alias

            break;
        default:
            if($segmentTwo) $alias = $segmentTwo; //article alias
            else $alias = $segmentOne; // menu alias
    }

    return $alias;
}


/****
 * formatMenu() formats menu array according parent-child
 * return an muti-level array
 ***/
function formatMenu($menu, $parent, $sub_trace=false){
    $menu2 = array();
    foreach($menu as $i => $item){
        if($item['parent'] == $parent){
            $menu2[$item['m_id']] = $item;
            if($sub_trace) $menu2[$item['m_id']]['is_sub']=TRUE;
            $menu2[$item['m_id']]['submenu'] = formatMenu($menu, $item['m_id'], true);
        }
    }
    return $menu2;
}


function getDateFormat($date, $db_date=false){
    if ($db_date) {
        // Database Date Format
        return date('Y-m-d', strtotime($date));
    }
    // View Date Format
    return date('d-m-Y', strtotime($date));
}

function getUrlSlug($id,$title){
    $slug = Str::slug($title, '-');
    return $id.'-'.$slug;
}
function getSlugById($slug){
    $id = explode('-',$slug);
    return $id[0];
}


/****
 * get_client_ip_env() get actual user ip/
 * return ip as string
 ***/
function get_actual_ip() {

    $ipAddress = '';

    if (getenv('HTTP_CLIENT_IP'))
        $ipAddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipAddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipAddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipAddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipAddress = getenv('REMOTE_ADDR');
    else
        $ipAddress = $_SERVER['REMOTE_ADDR'];

    if ((bool) filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE)) {

        return $ipAddress;

    }else return NULL;

}


/**
 * Primary Debuging
 * @param Array or Collection $data - Data Array
 * @param Str $way - Data dump or not
 */
function see($data = NULL, $way = NULL){
    if($way === 'dump' ){
        echo "<pre>"; var_dump($data); die();
    }else{
        if(is_null($data)) die(var_dump($data).'Detected');
        else
            echo "<pre>"; print_r($data); die();
    }
}





/*****
 * CI base_url()
 * Only for CI support
 */

function base_url($uri = ''){
    return url('/'.$uri);
}

/*****
 * show_404()
 * Page not found error. supported for CI 3
 */

function show_404(){
    return abort(404);
}



function getResourceRoute($controller, $array=''){
    $route =  Illuminate\Support\Facades\Route::class;

    for ($i=0; $i<count($array); $i++) {
        switch ($array[$i]) {

            case "index":
                $route::match(['get', 'post'], '/', [$controller,'index'])->name('');
                break;
            case "store":
                $route::post('/store', [$controller,'store'])->name('.store');
                break;
            case "create":
                $route::get('/create', [$controller,'create'])->name('.create');
                break;
            case "edit":
                $route::get('/{id}/edit', [$controller,'edit'])->name('.edit');
                break;
            case "update":
                $route::post('/update', [$controller,'update'])->name('.update');
                break;
            case "show":
                $route::get('/{id}', [$controller,'show'])->name('.show');
                break;
            case "delete":
                $route::match(['get', 'post'], '/delete/{id}', [$controller,'destroy'])->where(['id' => '[0-9]+'])->name('.delete');
                break;
        }
    }
}




function sqlQuery (){
    Schema::table('tbl_newsletter', function(\Illuminate\Database\Schema\Blueprint $table){
        $table->timestamp('created_at')->nullable()->after('n_created_at');;
        $table->timestamp('updated_at')->nullable();
    });
    Schema::table('category', function(\Illuminate\Database\Schema\Blueprint $table){
        $table->timestamp('created_at')->nullable()->after('cat_date');
        $table->tinyText('cat_description')->nullable();
        $table->timestamp('updated_at')->nullable();
    });

    Schema::table('category_article', function(\Illuminate\Database\Schema\Blueprint $table){
        $table->bigInteger('id')->autoIncrement()->primary()->first();
        $table->tinyInteger('status')->default(1)->after('r_catlead');
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
    });

    Schema::table('content', function(\Illuminate\Database\Schema\Blueprint $table){
        "ALTER TABLE `content` CHANGE `catid` `catid` INT(10) NULL DEFAULT '0'";
        "ALTER TABLE `content` CHANGE `hits` `hits` INT(10) NULL DEFAULT '0'";
        "ALTER TABLE `content` CHANGE `attribs` `attribs` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0'";
        $table->string('related_article')->nullable()->after('access');
        $table->text('seo')->nullable()->after('status');
        $table->timestamp('updated_at')->nullable()->after('status');;
    });

    Schema::table('menu', function(\Illuminate\Database\Schema\Blueprint $table){
        "ALTER TABLE `menu` CHANGE `m_showcat` `m_showcat` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
        $table->timestamp('created_at')->nullable()->after('m_date');;
        $table->timestamp('updated_at')->nullable();
    });

    Schema::table('menu_type', function(\Illuminate\Database\Schema\Blueprint $table){
        $table->timestamp('created_at')->nullable()->after('mt_tmpl');;
        $table->timestamp('updated_at')->nullable();
    });

    Schema::table('tbl_rating', function(\Illuminate\Database\Schema\Blueprint $table){
        $table->timestamp('created_at')->nullable()->after('ip_address');;
        $table->timestamp('updated_at')->nullable();
    });

    Schema::table('user_profiles', function(\Illuminate\Database\Schema\Blueprint $table){
        $table->string('directory')->nullable()->after('picture');
    });

//    Schema::table('users', function(\Illuminate\Database\Schema\Blueprint $table){
//        $table->string('username')->nullable()->after('id');
//    });

}


