<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'tbl_settings';
	protected $primaryKey = 's_id';
	public $timestamps = false;
}
