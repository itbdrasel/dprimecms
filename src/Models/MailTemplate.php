<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    protected $table = 'tbl_mail_template';

    protected $fillable = [
        'name', 'template', 'status'
    ];

    public static $sortable = ['id' => 'id', 'name' => 'name'];


}
