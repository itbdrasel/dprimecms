<?php

namespace Sourcebit\Dprimecms\Models;


use Illuminate\Database\Eloquent\Model;


class Persistence extends Model
{

    public function user(){
        return $this->belongsTo(User::class);
    }


}
