<?php

use Illuminate\Support\Facades\Mail;

use \Illuminate\Support\Facades\DB;


// Menu active class add
if (! function_exists('activeMenu')) {
    function activeMenu($sig, $data){
        return (Request::segment($sig) == $data)?'active':'';
    }
}
// Menu menu open class add
if (! function_exists('menuOpen')) {
    function menuOpen($sig, $data){
        if (is_array($data)) {
            for ($x = 0; $x < count($data); $x++) {
                if (Request::segment($sig) == $data[$x]) {
                    return 'menu-open';
                }
            }
        }else{
            return (Request::segment($sig) == $data)?'menu-open':'';
        }
    }
}


function permissionChecked($array, $object){
    $hotel_create= '';
    if (!empty($array) && !empty($array->$object) && $array->$object== true) {
        $hotel_create = 'checked';
    }
    return $hotel_create;
}


// User Password Generate
function passwordGenerator(){
    $digits = 2;
    $rand =  rand(pow(10, $digits-1), pow(10, $digits)-1);
    $password = 'P$'.date('s').'@'.$rand.'j';
    return $password;
}

// Email Send
// Email Send
if ( ! function_exists('emailSend')) {
    function emailSend($to, $subject, $data,$view='mail'){
        Mail::send($view, $data, function ($message) use ($to, $subject){
            return $message->to($to)->subject($subject);
        });
    }
}

function dbDateFormat($date){
    return date('Y-m-d', strtotime($date));
}
function getHours($startDate, $endDate=''){
    if (empty($endDate)) {
        $endDate = date('Y-m-d H:i:s');
    }
    $star = strtotime($startDate);
    $end = strtotime($endDate);
    return round(($end-$star) / 3600);

}