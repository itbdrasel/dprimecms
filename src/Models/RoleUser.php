<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public function roleName(){
        return $this->hasOne(Roles::class, 'id','role_id');
    }
}
