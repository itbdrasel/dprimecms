<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class EmailModel extends Model
{
    protected $table = 'tbl_mail';

    protected $fillable = [
        'mail_address', 'mail_subject', 'mail_user_id', 'mail_category','template_or_message'
    ];
    public static $sortable = ['id' => 'mail_id', 'subject' => 'mail_subject'];


    public function user(){
        return $this->belongsTo(User::class, 'mail_user_id','id');
    }




}
