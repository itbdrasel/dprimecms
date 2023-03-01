<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class HomeItem extends Model
{
    protected $table = 'tbl_home';

    protected $fillable = [
        'h_title', 'h_content', 'h_order', 'h_status', 'h_created_by'
    ];
    public static $sortable = ['id' => 'h_id', 'title' => 'h_title', 'order'=>'h_order'];

}
