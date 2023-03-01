<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class MenuType extends Model
{
    protected $table = 'menu_type';

    protected $fillable = [
        'mt_title', 'mt_alias', 'mt_date', 'mt_tmpl','updated_at'
    ];
    public static $sortable = ['id' => 'mt_id', 'title' => 'mt_title', 'place'=>'mt_alias', 'date'=>'mt_date'];

}
