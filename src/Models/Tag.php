<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tbl_tags';

    protected $fillable = [
        'name', 'status'
    ];
    public static $sortable = ['id' => 'id', 'name' => 'name'];




}
