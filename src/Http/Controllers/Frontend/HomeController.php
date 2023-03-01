<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Frontend;

use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Sourcebit\Dprimecms\Models\Article;
use Illuminate\Http\Request;
use Validator;
use Content;

class HomeController extends Controller
{

    private $data;
    public function __construct(){

    }

    public function index(){

        $this->data = [
            'title' => config('settings.appTitle'),
        ];


		$articleAlias = getAlias();

		//dd($articleAlias);

        //$articleAlias = segment(2);
        //if(empty($articleAlias)) $articleAlias = segment(1);

        if( !home() ){

			//echo Content::ratingView(Content::articleByAlias($article));

			$this->data['content_type'] = 'single';

			$menuCheck = Content::menuArticle($articleAlias);

			if( $menuCheck ){

				$this->data['menucheck'] = $menuCheck;

				if( $menuCheck->m_view === 'single' || $menuCheck->m_view === 'category' ){

					$this->data['content_type'] = 'single';

					$lnk = $menuCheck->m_link;

					//when category link
					if(preg_match('/\//', $lnk ) ){
						$l = explode('/',$lnk);
						$lnk = $l[1];
					}

					// Get menu article
					$content = Content::articleByAlias($lnk);

					if( !empty($content) ){

						//get cat_alias from returned data collection
						$catAlias = $content->categories->get(0)->cat_alias;

						//Article view count
						Article::viewcount($menuCheck->m_link);

						//Next and Previous Article
						//$this->data['next'] = Content::nextPrev($content['id'], 'next', $catAlias);
						//$this->data['previous'] = Content::nextPrev($content['id'],'prev', $catAlias );
						// Breadcrumb
						if( $content->categories[0]['cat_id'] ){
						 $catparent = $content->categories[0]['cat_id'];
						 //$this->data['breadcrumb'] = Content::breadcrumb($catparent, $content['title']);
						}

					}else{ show_404(); }

				}else if( $menuCheck->m_view === 'category' ){

					//code remove because routing problem.
					return redirect()->to($menuCheck->m_link);
				}else if( $menuCheck->m_view === 'external' ){
					//external link redirect
					return redirect()->to($menuCheck->m_link);
				}


			 /** *
			  * As the request page is not menu then check the Article directly.
			  */
            }else{
				$content = Content::articleByAlias($articleAlias);

				if(empty($content)) abort('404'); // article not found. 404

				$catAlias = $content->categories->get(0)->cat_alias;

				//Article view count
				Article::viewcount($content->alias);

				//Related articles for an article
				//$this->data['relatedArticles'] =  Content::relatedArticle($content->alias);

				//Next and Previous Article
				//$this->data['next'] = Content::nextPrev($content->id, 'next', $catAlias);
				//$this->data['previous'] = Content::nextPrev($content->id,'prev', $catAlias );

				// Breadcrumb
				if( $content->categories->get(0)->cat_id ){
					$catparent = $content->categories->get(0)->cat_id;
					//$this->data['breadcrumb'] = Content::breadcrumb($catparent, $content->title);
				}

			}


        }else{
			// Home Page - homeContent
			//$this->data['homeContent'] = Content::homeContent();
        }

		//content API
		$this->data['content'] = isset($content) ? (object)$content : '';

		if(!empty($content) ){
				//post actual url redirection if not match with current
				$actualUrl = postUrl($content->alias, $content->id);
				$currentUrl = urldecode(url()->current());

				if($content->is_menu != 1) //no redirection for menus
					if($actualUrl !== $currentUrl) return redirect($actualUrl);
		}


		// seo optimization
		if(isset($content) && !empty($content) ){
			$this->data['meta'] = Content::metaData($content, 'page') ;
		}else{
			$pageMeta = [
				'metaImage' => url(config('settings.logo')),
				'metaKeyword' => config('settings.appName'),
				'metaDescription' => config('settings.description'),
				'metaTitle' => config('settings.appTitle'),
			];

			$this->data['meta'] = Content::metaData($pageMeta);
		}

        echo $this->layout('index');
    }


    public function layout($pageName){
        echo view('frontend.'.$pageName.'', $this->data);
    }

	/* * *
	* FrontEnd Article Rating
	*
	* */
	public function rate(Request $request){

		if( !$request->ajax() ) die('Access Denied!');

		$rules = [
			'postId'     => 'required|integer',
			'rating'     => 'required|integer',
		];

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()){
			echo "Rating data is not valid!";
		}else{

			$postId = $request['postId'];
			$rating = $request['rating'];

			if( Content::rateAction($postId, $rating) ){
				echo Content::ratingView(Content::articleById($postId));
			}else{
				echo "You have already rated. Thanks.";
			}
		}

    }


}
