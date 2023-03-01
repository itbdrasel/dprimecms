<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;


class BaseSetting extends Model
{

    protected $table = 'tbl_settings';
	
	protected $fillable = [
        's_name',
        's_value'
    ];

}
