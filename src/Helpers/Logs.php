<?php
namespace Sourcebit\Dprimecms\Helpers;

use Illuminate\Support\Facades\DB;
use Sentinel;

class Logs{



    public  static function create($title, $type, $log_amount=0, $log_creator='', $log_date='' ){
        if (empty($log_creator)) {
            $log_creator= Sentinel::getUser()->id;
//
        }
        if (empty($log_date)) {
            $log_date =   date("Y-m-d H:m:s");
        }
        $data = [
            'log_title'     => $title,
            'log_type'      => $type,
            'log_amount'    => $log_amount,
            'log_creator'   => $log_creator,
            'log_date'      => $log_date,
            'log_ip'        => get_actual_ip()
        ];

        DB::table('tbl_logs')->insert($data);

	}


    public static function destroy()
    {
        DB::table('tbl_logs')->truncate();
    }


}


?>
