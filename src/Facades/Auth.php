<?php

namespace Sourcebit\Dprimecms\Facades;

use Illuminate\Support\Facades\Facade;

class Auth extends Facade
{
    protected static function getFacadeAccessor()
    {        
        return 'Sourcebit\Dprimecms\Repositories\AuthRepository';
    }
    
}