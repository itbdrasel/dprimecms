<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contact_us';

    protected $fillable = [
        'name', 'phone', 'email', 'subject', 'message','validate', 'status'
    ];
    public static $sortable = ['id' => 'id', 'name' => 'name'];

}
