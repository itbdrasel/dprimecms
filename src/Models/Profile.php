<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [
        'full_name','p_uid', 'firstname', 'lastname', 'description', 'picture', 'directory'
    ];



}
