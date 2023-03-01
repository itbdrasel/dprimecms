<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'parent', 'm_type', 'm_title', 'm_alias', 'm_link', 'm_template', 'm_order', 'm_view', 'm_showcat', 'm_attribs', 'm_status', 'm_date'
    ];
    public static $sortable = ['id' => 'm_id', 'title' => 'm_title', 'place'=>'mt_alias', 'date'=>'mt_date'];

    public function MenuType(){
        return $this->belongsTo(MenuType::class, 'm_type','mt_id');
    }

}
