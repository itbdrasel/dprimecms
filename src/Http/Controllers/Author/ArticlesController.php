<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Helpers\HtmlEntities;
use Sourcebit\Dprimecms\Helpers\Logs;
use Sourcebit\Dprimecms\Models\Article;
use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Sourcebit\Dprimecms\Models\ArticleCategory;
use Sourcebit\Dprimecms\Models\Category;
use Sourcebit\Dprimecms\Models\Tag;
use Illuminate\Http\Request;
use Validator;

class ArticlesController extends Controller
{

    private $model;
    private $data;
    private $tableId;
    private $bUrl;

    public function __construct(){
        $this->tableId = 'id';
        $this->model = Article::class;
        $this->bUrl = 'author/articles';
    }

    public function layout($pageName){
        $this->data['tableID'] = $this->tableId;
        $this->data['bUrl'] = $this->bUrl;
        echo view('sourcebit::author.articles.'.$pageName.'', $this->data);
    }


    public function index(Request $request)
    {

        $this->data = [
            'title'         => 'Article Manager',
            'pageUrl'       => $this->bUrl,
            'page_icon'     => '<i class="fa fa-book"></i>',
            'categories'    => Category::all()
        ];

        //result per page
        $perPage = session('per_page') ?: 20;

        //table item serial starting from 0
        $this->data['serial'] = ( ($request->get('page') ?? 1) -1) * $perPage;

        if($request->method() === 'POST'){
            session(['per_page' => $request->post('per_page') ]);
        }

        //model query...
        $queryData = $this->model::orderBy( getOrder($this->model::$sortable, $this->tableId)['by'], getOrder($this->model::$sortable, $this->tableId)['order'])
        ->with(['categories', 'user']);

        //filter posts

        /* if( $request->hasAny(['filter', 'category']) ){

            $this->data['filter'] = $filter = $request->get('filter');
            $this->data['categoryId'] = $category = $request->get('category');

            $queryData->whereHas('categories.articles', function ($queryData) use ($category) {
                    $queryData->where('cat_id', '=',  $category);
                })
                ->orWhere('title', 'like', '%'.$filter.'%')
            ;
        } */

        //filter by post title .....
        if( $request->filled('filter') ){

            $this->data['filter'] = $filter = $request->get('filter');

            $queryData->orWhere('title', 'like', '%'.$filter.'%');
        }

        //filter by category .....
        if( $request->filled('category') ){

            $this->data['categoryId'] = $category = $request->get('category');

            $queryData->whereHas('categories.articles', function ($queryData) use ($category) {
                    $queryData->where('cat_id', '=',  $category);
                });
        }

        $this->data['allData'] =  $queryData->paginate($perPage)->appends( request()->query() ); // paginate

        $this->layout('index');

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->data = [
            'title'         => 'Add New Article',
            'page_icon'     => '<i class="fa fa-book"></i>',
            'pageUrl'       => $this->bUrl.'/create',
            'objData'       => '',
            'categories'    => Category::where('cat_status','active')->orderBy('cat_title','ASC')->get(),
            'relatedArticles' => '',
        ];

        $this->data['tags'] = Tag::where('status',1)->get();

        $this->layout('create');
    }


    // edit
    public function edit($id)
    {

        $id = filter_var($id, FILTER_VALIDATE_INT);
        $objData = $this->model::where($this->tableId, $id)->with('categories')->first();

        if( !$id || empty($objData)){ exit('Bad Request!'); }

        $this->data = [
            'title'         => 'Edit Article',
            'page_icon'     => '<i class="fa fa-edit"></i>',
            'pageUrl'       => $this->bUrl.'/'.$id.'/edit',
            'objData'       => $objData,
            'categories'    => Category::where('cat_status','active')->orderBy('cat_title','ASC')->get(),
        ];

        $this->data['relatedArticles']  = $this->relatedArticles($objData->title);

        $this->data['tags'] = Tag::where('status',1)->get();

        $this->layout('create');
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $id = $request[$this->tableId];

        $rules = [
            'title'         => 'required',
            'alias'         => 'required|unique:content,alias',
            'fulltexts'     => 'required',
            'access'        => 'required',
            'status'        => 'required',
            'category'      => 'required',
        ];

        if ( !empty($id) ) {
            $rules['alias']= 'required|unique:content,alias,'. $id .',id';
        }

        $attribute = [
            'fulltexts'=>'Intro Text'
        ];

        $customMessages = [];

        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $relatedArticles = $request['related_article'] ? json_encode($request['related_article']) : NULL;

        $tags = $request['tags'] ? json_encode($request['tags']) : NULL;

        $introText = !empty($request['introtext']) ?  strip_tags($request['introtext']) : '';

        $data = [
            'title'             => $request['title'],
            'alias'             => strip_tags($request['alias']),
            'introtext'         => $introText,
            'fulltexts'         => $request['fulltexts'],
            'metakeys'          => strip_tags($request['metakeys']),
            'metades'           => strip_tags($request['metades']),
            'featuredimg'       => $request['featuredimage'],
            'seo'               => $request['seo'],
            'access'            => $request['access'],
            'status'            => $request['status'],
            'canonical'         => $request['canonical'],
            'related_article'   => $relatedArticles,
            'tags'              => $tags
        ];

        $category = $request['category'];

        $logTitle = 'Article ('.$request['title'].')';

        if ( empty($id) ){

            // Insert Query
            $data['created_by'] = dAuth()->getUser()->id;
            $data ['hits'] = 0;

            $articleId = $this->model::create($data)->id;

            $this->createArticleCategory($articleId, $category);

            Logs::create($logTitle, 'create');

        }else{

            $this->model::where($this->tableId, $id)->update($data);

            //delete article category first
            ArticleCategory::where('r_aid', $id)->delete();

            $this->createArticleCategory($id, $category);

            Logs::create($logTitle, 'update');

        }

        $editUrl = $this->bUrl.'/'.( empty($id) ? $articleId : $id ).'/edit';
        $redirectUrl = ($request['submit'] !== 'save-n-exit') ? $editUrl : $this->bUrl;

        $successMgs = empty($id) ? 'Article Successfully Created' : 'Artile Successfully Updated';

        return redirect($redirectUrl)->with('success', $successMgs);

    }


    public function createArticleCategory($articleId, $category){


        if ( !empty($category) ){
            foreach($category as $item){
                $articleCategories = [
                    'r_catid'=> $item,
                    'r_aid'=> $articleId,
                ];
                ArticleCategory::create($articleCategories);
            }
        }
    }


    /* * *
    * View Article Artibutes
    * @param int $id - Article ID.
    *
    * @return Renderable
    **/

    public function show( $id ){

        $id = filter_var($id, FILTER_VALIDATE_INT);

        $objData = $this->model::where($this->tableId, $id)->first();

        if( !$id || empty($objData)){ exit('Bad Request!'); }

        $tags_id = json_decode($objData->tags, true)??[];
        $r_a_id = json_decode($objData->related_article, true)??[];

        $this->data = [
            'title'         => 'View Article',
            'pageUrl'       => $this->bUrl,
            'bUrl'          => $this->bUrl,
            'page_icon'     => '<i class="fa fa-eye"></i>',
            'objData'       => $objData,
            'tags'              => Tag::whereIn('id', $tags_id)->get(),
            'related_article'   => $this->model::whereIn('id', $r_a_id)->get()
        ];

        $this->layout('view');
    }


   /**
     * Post attributes Control
     * @param Request $request
     * @param Optional $id
     * @return Renderable
     */
	public function attribute(Request $request, $id = ''){

		if(empty($id)){ abort('404'); exit; };

		//define valid url params
		$urlParams = array('featured','suggested');

        $getAttr = $this->model::select('attribs')->where('id', '=', $id)->first();

		$attr = [];

		if( empty( $getAttr->attribs ) ){

			foreach($request->all() as $key => $value){

				//check valid url params
				if(in_array($key, $urlParams)){
					$attr[$key] = (int) $value;
				}
			}

			$jf = json_encode($attr);
            $this->model::where($this->tableId, $id)->update( ['attribs' =>  $jf] );

		}else{

			//decode encoded attributes
			$jdf = json_decode( $getAttr->attribs, true);

			// process all params
			foreach($request->all() as $key => $value){

				//check valid url params
				if( in_array($key, $urlParams) ){
					if(array_key_exists($key, $jdf)){
						$jdf[$key] = (int) $value;
					}else{
						$jdf[$key] = (int) $value;
					}
				}
			}

			$data = array('attribs' =>  json_encode($jdf) );

            $this->model::where($this->tableId, $id)->update( $data );
		}

        return redirect( $this->bUrl)->with('success', 'Action Successful.');
	}


    public function categoryLead( Request $request, $id = '' ){

		$categoryId = $request->get('cid');
		$articleId = $id;

		if(isset($articleId) && !empty($articleId) && isset($categoryId) && !empty($categoryId)){

            $getIds = ArticleCategory::select('r_aid')->where('r_catid', '=', $categoryId)->get();

			if( empty($getIds) ) show_404();

			$ids = [];

			foreach($getIds as $id){
				$ids[] = $id['r_aid'];
			}

            ArticleCategory::whereIn('r_aid', $ids)->update( ['r_catlead' => 0] );

            $where = [ 'r_aid' => $articleId, 'r_catid' => $categoryId ];
            ArticleCategory::where($where)->update( [ 'r_catlead' => 1 ] );

            return redirect( $this->bUrl)->with('success', 'Action Successful.');

		}else { show_404(); }

	}


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

    public function destroy(Request $request, $id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if( !$id ){ exit('Bad Request!'); }

        $this->data = [
            'title'     => 'Delete Article',
            'pageUrl'   => $this->bUrl.'/delete/'.$id,
            'page_icon' => '<i class="fa fa-trash"></i>',
            'objData'   => $this->model::where($this->tableId, $id)->first(),
        ];

        if(empty($this->data['objData'])){ exit('Bad Request!'); }

        $this->data['tableID'] = $this->tableId;
        $this->data['bUrl'] = $this->bUrl;

        if($request->method() === 'POST' ){

            $this->model::where($this->tableId, $id)->delete();

            ArticleCategory::where(['r_aid'=>$id])->delete();

            //logs
            Logs::create('Article ('.$this->data['objData']->title.')','delete');

            echo json_encode(['fail' => FALSE, 'error_messages' => "Article was deleted."]);
        }else{

            return view('sourcebit::author.articles.delete', $this->data);

        }

    }

    /*  */
    public function relatedArticles($titles){

        $titles = trim($titles);

        $titles = explode(" ", $titles);

        if (!empty($titles)){
            $query = $this->model::orderBy('title')->select('id', 'title', 'alias');
            foreach ($titles as $value){
                $query->orWhere('title', 'like',  '%'.$value.'%');
            }
            return $query->get();
        }
    }

    public function postMenu($id){
        $id = filter_var($id, FILTER_VALIDATE_INT);
        $objData = $this->model::where($this->tableId, $id)->first();

        if( !$id || empty($objData)){ exit('Bad Request!'); }
        $this->data = [
            'title'         => 'Article Post Menu',
            'pageUrl'       => $this->bUrl.'/post-menu/'. $id,
            'page_icon'     => '<i class="fa fa-book"></i>',
            'objData'       => $objData
        ];
        $this->layout('post_menu');
    }
    public function postMenuStore(Request $request, $id){
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if( !$id){ exit('Bad Request!'); }
        $post_menus = $request['text'];
        $post_menu = [];
        if (!empty($post_menus)) {
            foreach ($post_menus as $key=>$value){
                if (!empty($value)) {
                    $post_menu[]=  [
                        'text'=>$value,
                        'id'=>$request['id'][$key],
                    ];
                }
            }
        }

        $this->model::where($this->tableId, $id)->update(['post_menus'=>json_encode($post_menu)] );
        return redirect( $this->bUrl)->with('success', 'Action Successful.');

    }
}
