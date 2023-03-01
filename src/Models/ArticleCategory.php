<?php

namespace Sourcebit\Dprimecms\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleCategory extends Pivot
{
    protected $table = 'category_article';

    protected $fillable = [
        'r_catid', 'r_aid', 'r_catlead'
    ];

}
