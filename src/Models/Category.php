<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'cat_id';

    protected $fillable = [
        'cat_title', 'cat_alias', 'cat_picture', 'cat_parent', 'cat_description', 'cat_status', 'cat_date'
    ];

    public static $sortable = ['id' => 'cat_id', 'title' => 'cat_title'];

    public function articles(){
        return $this->belongsToMany(Article::class, 'category_article', 'r_catid', 'r_aid');
    } 

}
