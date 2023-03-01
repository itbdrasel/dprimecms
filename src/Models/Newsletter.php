<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table = 'tbl_newsletter';

    protected $fillable = [
        'n_name', 'n_email', 'n_ip_address', 'n_status', 'n_validate','n_key', 'n_unsbs', 'n_verf_mail_count', 'n_mail_send_no', 'n_score', 'n_created_at'
    ];
    public static $sortable = ['status' => 'n_status', 'mail' => 'n_email'];




}
