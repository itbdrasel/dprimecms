<?php

namespace Sourcebit\Dprimecms\Facades;

use Illuminate\Support\Facades\Facade;

class Content extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Sourcebit\Dprimecms\Services\ContentService';
    }

}
