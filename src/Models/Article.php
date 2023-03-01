<?php

namespace Sourcebit\Dprimecms\Models;


use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'content';

    protected $fillable = [
        'title', 'alias', 'introtext', 'fulltexts', 'featuredimg', 'catid', 'catlead', 'hits', 'attribs', 'post_menus','created_by', 'metakeys', 'metades', 'canonical', 'is_menu', 'access', 'related_article', 'tags', 'seo', 'status'
    ];

    public static $sortable = ['id' => 'id', 'title' => 'title','date'=>'created_at'];

    public function setTags()
    {
        return json_decode($this->tags, true);
    }





/*     public function articleCategories(){
        return $this->hasMany(ArticleCategory::class, 'r_aid','id');
    } */

    public function categories(){
        //category_aticle is defined to override the default.
        //The third argument is the foreign key name of the model on which you are defining the relationship, 
        //while the fourth argument is the foreign key name of the model that you are joining to

        return $this->belongsToMany(Category::class, 'category_article', 'r_aid', 'r_catid')
                    ->withPivot('r_catlead');
    }    

    public function user(){
        return $this->belongsTo(User::class, 'created_by','id');
    }


    /* * *
     * Get Single Article by checking alias, status and access 
     * 
     * @return Model Instance or NULL
     */

    public function scopeArticle($query, $slug){
    
        $where = ['alias' => $slug, 'status' => 'active', 'access' => 1];

        return $query->with(['categories', 'user'])->whereHas('categories', function($q){		
          $q->where('cat_status', 'active');
        })->where($where)->first();

    }


    // Articles hits/views incrementer.
    public function scopeViewCount($query, $articleAlias){
        $query->where('alias', $articleAlias )->increment('hits');
    }

    






}
