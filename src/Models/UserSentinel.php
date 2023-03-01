<?php

namespace Sourcebit\Dprimecms\Models;

use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;


class UserSentinel extends SentinelUser
{
    protected $fillable = [
        'email',
        'username',
        'password',
        'last_name',
        'first_name',
        'permissions',
        'full_name',
        'phone',
        'status',        
    ];
}
