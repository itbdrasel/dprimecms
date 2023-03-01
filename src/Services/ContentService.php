<?php

namespace Sourcebit\Dprimecms\Services;

use Sourcebit\Dprimecms\Models\Article;
use Sourcebit\Dprimecms\Models\Category;
use Sourcebit\Dprimecms\Models\HomeItem;
use Sourcebit\Dprimecms\Models\Menu;
use Sourcebit\Dprimecms\Models\Rating;
use Sourcebit\Dprimecms\Models\Tag;
use Illuminate\Http\Request;

class ContentService{

    public function __construct(){

    }


     /***
     * Get Article By its alias
     * By Calling Model Scope Method
     *
     * @return Model Instance or NULL
     **/
    public function articleByAlias($alias){

        $where = ['alias' => $alias, 'status' => 'active', 'access' => 1];

        return Article::with(['categories'])->whereHas('categories', function($q){
          $q->where('cat_status', 'active');
        })->where($where)->first();

        //return Article::article($alias);
    }


     /***
     * Get Article By its alias
     * By Calling Model Scope Method
     *
     * @return Model Instance or NULL
     **/
    public function categoryByAlias($alias){
        return Category::where(['cat_alias' => $alias, 'cat_status'=>'active' ])->first();
    }

    /***
     * Get Article By its ID
     *
     * @return Model Instance or NULL
     **/
    public function articleById($articleId){

        $where = ['id' => $articleId, 'status' => 'active', 'access' => 1];

        return Article::with(['categories'])->whereHas('categories', function($q){
          $q->where('cat_status', 'active');
        })->where($where)->first();
    }


    /***
     * Get all Articles of a Category Item
     * Access the articles $category->articles()->paginate(10);
     * @param $categoryAlias
     * @param Optional int $limit
     *
     * @return Model Instance
     **/
//    public function articlesByCategory($categoryAlias, int $limit = 20){
    public function articlesByCategory($categoryAlias,  $limit = 20){
        $where = ['content.status' => 'active', 'access' => 1];
        $whereCategory = ['cat_status' => 'active', 'cat_alias' => $categoryAlias];

        return Category::where($whereCategory)->whereHas('articles', function($q) use ($where){
            $q->where( $where )->orderBy('content.id','DESC');
           })->first();
    }

    /***
     * Get All Home items
     * For Home page content
     *
     * @return Collection
     **/
    public function homeContent(){

        return HomeItem::where(['h_status' => 1])->orderBy('h_order','ASC' )->get();
    }


    /***
     * Check if the Article Linked with Menu or Not
     *
     * @return Model Instance or NULL
     **/
    public function menuArticle($article){
        return Menu::where(['m_alias' => $article, 'm_status' => 'active'])->first();
    }


    /***
     * Get recent Published Articles
     *
     * @return Model Collection
     **/
    public function recentArticles($articleNum = 10){

        $where = ['status' => 'active', 'access' => 1, 'is_menu' => 0];

        return Article::with(['categories'])->whereHas('categories', function($q){
          $q->where('cat_status', 'active');
        })->where($where)->limit($articleNum)->orderBy('id', 'DESC')->get();

    }


    /***
     * Get Attributed Articles like featured, Suggested etc.
     * @param String Optional $attribution - (default 'featured') 'featured', 'suggested'
     * @param Int Optional $articleNum - (default 10) Number of post want to.
     *
     * @return Model Collection
     **/
    public function attributedArticles($attribution = '', $articleNum = 10){

        if(empty($attribution) ) $attribution = 'featured';

        $where = ['status' => 'active', 'access' => 1, 'is_menu' => 0];

        return Article::with(['categories'])->whereHas('categories', function($q){
          $q->where('cat_status', 'active');
        })->where($where)->whereRaw('JSON_CONTAINS(attribs, "1", "$.'.$attribution.'")')
                        ->limit($articleNum)->orderBy('id', 'DESC')->get();

    }




    public function mostRead(){

    }


    /***
     * ________ TAG Control _____________
     * Get Tag Names of an Article.
     *
     * @return Model Collection or NULL
     **/

    public function getTagNames($articleAlias){

        $article =  $this->articleByAlias($articleAlias);

        if( !empty($article) ){
            $tags = json_decode($article->tags);
            if($tags) return Tag::whereIn('id', $tags)->get();
        }
    }
    /***
     * Get Tag Id from tag name
     *
     * @return Model Instance or null
     **/
    public function tagIdByName($tagName){
        return Tag::where(['name' => $tagName, 'status' => '1'])->first();
    }


    /***
     * Get Articles by Tag Name
     *
     * @return Model Instance or null
     **/
    public function articleByTag($tagName, $articleNum = NULL){

        $tagId = $this->tagIdByName($tagName);

        if( !empty($tagId) ){

            $articleNum = !empty($articleNum) ? $articleNum : 10;
            $where = ['status' => 'active', 'access' => 1, 'is_menu' => 0];

            return Article::with(['categories'])->whereHas('categories', function($q){
            $q->where('cat_status', 'active');
            })->where($where)->whereJsonContains('tags',  "$tagId->id")->limit($articleNum)->orderBy('id', 'DESC')->get();
        }
    }



    public function viewCount($alias){
        return Article::viewcount($alias);
    }

    public function getViewCount($alias){
        return Article::where('alias', $alias )->first()->hits;
    }


    /***
     * nextPrev() - get the next and previous article.
     *
     * @return Model Instance or NULL
     **/
    public function nextPrev($articleId, $nextPrev, $catAlias = ''){

        $where = [
            ['is_menu', 0],
            ['status', 'active'],
        ];

        $whereCagegory = [
            ['cat_status', 'active']
        ];

        if($catAlias) $whereCagegory[]= ['cat_alias', $catAlias];

        if($nextPrev == 'next') $where[]= ['id', '>', $articleId];
        elseif($nextPrev == 'prev') $where[]= ['id', '<', $articleId];

       //dd($whereCagegory);

        return Article::with(['categories'])->whereHas('categories', function($q) use ($whereCagegory){
            $q->where($whereCagegory);
        })->where($where)->orderBy('id','ASC')->first();
    }

    /***
     * Get the next article.
     *
     * @return Model Instance or NULL
     **/
    public function next($alias, $catAlias = ''){
        $article = $this->articleByAlias($alias);
        return $this->nextPrev($article->id, 'next', $catAlias);
    }
      /***
     * Fet the Previous Article
     *
     * @return Model Instance or NULL
     **/
    public function previous($alias, $catAlias = ''){
        $article = $this->articleByAlias($alias);
        return $this->nextPrev($article->id, 'prev', $catAlias);
    }



    /***
     * @function - breadcrumb()
     * @return page breadcrumb htmls
     **/
    public function breadcrumb($catId, $title = ''){
        $catparent = $catId;
        $breadcrumb = "<a href=".base_url()."><i class='fa fa-home'></i></a>";
        $bread_last = '';
        $c = 0;

        do{
            $where = [ 'cat_id'=> $catparent, 'cat_status' => 'active' ];
            $category = Category::where($where)->first();

            $catparent = $category['cat_parent'];

            if($c != 0){
                $breadcrumb.=" > <a href=".base_url($category['cat_alias']).">".$category['cat_title']."</a>";
            }elseif($category['cat_title'] != 'uncategorized'){
                $bread_last=" > <a href=".base_url('section/'.$category['cat_alias']).">".$category['cat_title']."</a>";
            }

            $c++;
        }while($catparent!='root');

        $breadcrumb .= !empty($title) ? $bread_last." > ".$title : $bread_last;

        return $breadcrumb;

    }


    /***
     * Provide the related post of a post. First look in the related field.
     * if not found then guess from the article title.
     * return array
     */
    function relatedArticle($alias, $category = '', $catTitle = '') {


        if( $related_articles = $this->dbRelatedArticles($alias)){
            return $related_articles;
        }else{
            //guess Related Articles
            $article_no = 5;
            $article = $this->articleByAlias($alias);
            $articleTitle =  $article->title;

            if(preg_match('/\s/i', $articleTitle)){
                $tags = explode(' ', $articleTitle);
            }

            $getArticles = Article::with(['categories'])
                                ->orWhere('title', 'LIKE', '%'.$articleTitle.'%');

            if($catTitle) $getArticles->orWhere('title', 'LIKE', '%'.$catTitle.'%');

            if(isset($tags) && !empty($tags) ){
                foreach($tags as $tag){
                    $getArticles->orWhere('title', 'LIKE', '%'.$tag.'%');
                }
            }

            $getArticles->where('title', '!=', $articleTitle)
                        ->where('status', '=', 'active')
                        ->where('access', '=', 1)
                        ->where('is_menu', '=', 0);

            if(isset($category) && !empty($category) ){

                $related_articles = $getArticles->orderBy('created_at', 'DESC')
                                    ->take($article_no)
                                    ->skip(0)
                                    ->get();
            }else{
                $related_articles = $getArticles->get();
            }

            if( isset($related_articles ) && !empty( $related_articles ) ){

                for( $i=0; $i < count($related_articles); $i++){
                    if($related_articles->pluck('title')->contains($articleTitle) ){
                        $related_articles->forget($i);
                    }
                }
            }
            return $related_articles;
        }

    }

    /***
     * Provide related post from article related field.
     * return array
     */
    public function dbRelatedArticles($alias){

        $article = $this->articleByAlias($alias);

        $relatedArticle = NULL;

        if($article->related_article){
            $relateArticleIds = json_decode($article->related_article, true);

            $where = ['status' => 'active', 'access' => 1, 'is_menu' => 0];

            $relatedArticle =   Article::with(['categories'])->whereHas('categories', function($q){
              $q->where('cat_status', 'active');
            })->where($where)->whereIn('id', $relateArticleIds)->get();
        }

        return $relatedArticle;
    }


    // Get all Articles of a Category
    public function categoryArticles($catSlug){

		$where = ['status' => 'active', 'access' => 1];
		$whereCategory = ['cat_status' => 'active', 'cat_alias'=> $catSlug ];

		return Article::with(['categories', 'user'])->whereHas('categories', function($q) use ($whereCategory){
		  $q->where($whereCategory);
	  	})->where($where)->get();

    }


    /***
     * Page Meta Data
     * @param array|object $contentPage - Page Data or Page Meta
     * @param string $contentType
     *
     * @return Object
     */
    public function metaData($contentPage, $contentType = ''){

        $uriString = implode('/', request()->segments());

        $metaData = [
            'metaUrl' => url($uriString),
            'metaImage' => '',
            'metaKeyword' => '',
            'metaDescription' => '',
            'metaTitle' => '',
            'metaSnippet' => '',
            'canonical' => url($uriString),
        ];

        if( $contentType === 'section' ){
            //category or section page meta
            $metaData['metaTitle'] = $contentPage->cat_title." | ".config('settings.appName');
            $metaData['canonical'] =  url()->current();
            $metaData['metaImage'] = $contentPage->cat_picture ? url($contentPage->cat_picture) : '';
            $metaData['metaKeyword'] = str_replace( ['&'], [','], $contentPage->cat_title);

            if( strlen($contentPage->cat_description) > 200 ){
                $metaData['metaDescription'] = text_num( html_entity_decode( strip_tags($contentPage->cat_description) ), 200 );
            }else $metaData['metaDescription'] = html_entity_decode( strip_tags($contentPage->cat_description) );

        }elseif($contentType === 'multi'){
            //multi page meta
            $metaData['metaTitle'] = $contentPage->categories->cat_title." | ".config('settings.appName');
            $metaData['metaKeyword'] =   str_replace( ['&'], [','], $contentPage->cat_title);
            $metaData['metaDescription'] = strip_tags($contentPage->cat_description);

        }elseif($contentType === 'page'){
            //regular page meta
            $metaData['metaTitle'] = $contentPage->title." | ".config('settings.appName');
            $metaData['metaSnippet'] = $contentPage->seo;

            $canonicalUrl = $contentPage->canonical ? $contentPage->canonical : postUrl($contentPage->alias, $contentPage->id);
            $metaData['canonical'] =  $canonicalUrl;

            $metaData['metaImage'] = $contentPage->featuredimg ? url($contentPage->featuredimg) : '';

            if(empty($contentPage->metakeys)){
                $keywords = '';
                $keywords .= str_replace(' ',',', $contentPage->title);
                $keywords .= str_replace(' ',', ',substr($contentPage->introtext, 0, 500));
                $metaData['metaKeyword'] = $keywords;
            }else{
                $metaData['metaKeyword'] = $contentPage->metakeys;
            }

            if( empty( $contentPage->metades ) ){
                $metaData['metaDescription'] = $contentPage->introtext;
            }else{
                $metaData['metaDescription'] = $contentPage->metades;
            }

        }else $metaData = array_merge($metaData, $contentPage);

        return (object) $metaData;
    }





    /*
    * Content rating plugin
    */

    public function getRating($article){
        return Rating::getRating($article->id);
    }

    public function rateAction($postId, $vote){

        $rateData = [
            'vote' => $vote,
            'postId' => $postId,
            'ipAddress' => get_actual_ip(),
        ];

        return Rating::rateAction($rateData);
    }


    public function ratingView($article){

        $ratingData = $this->getRating($article);

        $stars = '';
        for ($i = 1; $i <= floor($ratingData['totalRatings']); $i++) {
            $stars .= '<div class="star" id="' . $i . '"></div>';
        }

        $rating_html = '<input type="hidden" name="postId" id="postId" value="'.$article->id.'"/>
        <div class="r"><div class="rating">' . $stars . '</div>' .
        '<div class="transparent">
            <div class="star" id="1"></div>
            <div class="star" id="2"></div>
            <div class="star" id="3"></div>
            <div class="star" id="4"></div>
            <div class="star" id="5"></div>
            <div class="votes"> &nbsp; (<span itemprop="ratingValue">' . $ratingData['totalRatingsRound'] . '</span>/5, <span itemprop="ratingCount">' . $ratingData['ratingNumbers'] . ' ' . ($ratingData['ratingNumbers'] > 1 ? ' </span>votes' : ' </span>vote') . ') '.'</div>
        </div>
        </div>';

        if($_POST) echo $rating_html; else return $rating_html;
    }



}
