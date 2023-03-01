<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class RoleSections extends Model
{
    protected $table = 'tbl_module_sections';
	
	protected $fillable = [
        'section_name'
    ];

}
