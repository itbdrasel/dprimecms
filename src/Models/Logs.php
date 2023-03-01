<?php

namespace Sourcebit\Dprimecms\Models;


use Illuminate\Database\Eloquent\Model;


class Logs extends Model
{

    protected $table = 'tbl_logs';
	
	protected $fillable = [
        'log_title', 'log_type', 'log_ip', 'log_amount', 'log_creator', 'log_date'
    ];
    public static $sortable = ['id' => 'log_id', 'title' => 'log_title','type'=>'log_type','date'=>'log_date','user'=>'log_creator'];

    public function user(){
        return $this->hasOne(User::class,'id','log_creator');
    }


}
