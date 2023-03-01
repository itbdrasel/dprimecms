<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Helpers\Logs;
use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Sourcebit\Dprimecms\Models\Article;
use Sourcebit\Dprimecms\Models\Category;
use Sourcebit\Dprimecms\Models\Menu;

use Sourcebit\Dprimecms\Models\MenuType;
use Session;
use Illuminate\Http\Request;
use Validator;

class MenuController extends Controller
{

    private $model;
    private $data;
    private $tableId;
    private $bUrl;
    private $title;

    public function __construct(){

        $this->tableId = 'm_id';
        $this->model = Menu::class;
        $this->bUrl = 'author/menu';
        $this->title = 'Menu';

    }

    public function layout($pageName){
        $this->data['tableID'] = $this->tableId;
        $this->data['bUrl'] = $this->bUrl;
        echo view('sourcebit::author.menu.'.$pageName.'', $this->data);
    }


    public function index(Request $request)
    {

        $this->data = [
            'title'         => $this->title.' Manager',
            'pageUrl'         => $this->bUrl,
            'page_icon'     => '<i class="fa fa-book"></i>',
            'parent_menu'   => $this->model::where('parent','!=','Root')->orderBy('m_order', 'asc')->with('MenuType')->get(),
        ];

        //result per page
        //$perPage = session('per_page') ?: 20;

        //table item serial starting from 0
        $this->data['serial'] = ( ($request->get('page') ?? 1) -1);


        if($request->method() === 'POST'){
            session(['per_page' => $request->post('per_page') ]);
        }
        //model query...
        $asc_desc ='ASC';
        $order = "m_order";
        if (!empty($request['order'])) {
            if ($request['order'] ==1) {
                $asc_desc ='DESC';
            }
        }
        $queryData = $this->model::orderBy($order, $asc_desc)->where('parent','Root')->with('MenuType');

        //filter by text.....
        if( $request->filled('filter') ){
            $this->data['filter'] = $filter = $request->get('filter');
            $queryData->where('m_title', 'like', '%'.$filter.'%');
        }

        $this->data['allData'] =  $queryData->get(); // paginate

        $this->layout('index');

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->data = [
            'title'         => 'Add New '.$this->title,
            'page_icon'     => '<i class="fa fa-book"></i>',
            'menu_types'    => MenuType::orderBy('mt_title', 'ASC')->select('mt_id', 'mt_title')->get(),
            'pages'         => Article::select('id', 'title', 'alias')->orderBy('title','ASC')->get(),
            'categories'    => Category::select('cat_id', 'cat_title', 'cat_alias')->orderBy('cat_title','ASC')->get(),
            'parent_menus'  => Menu::select('m_id', 'm_title')->orderBy('m_title','ASC')->get(),
            'menu_order'    => Menu::select('m_title','m_order')->orderBy('m_order','ASC')->get(),
            'objData'       => '',
        ];
        $this->layout('create');
    }


    public function edit($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if( !$id ){ exit('Bad Request!'); }

        $this->data = [
            'title'         => 'Edit '.$this->title,
            'page_icon'     => '<i class="fa fa-edit"></i>',
            'objData'       => $this->model::where($this->tableId, $id)->first(),
            'menu_types'    => MenuType::orderBy('mt_title', 'ASC')->select('mt_id', 'mt_title')->get(),
            'pages'         => Article::select('id', 'title', 'alias')->orderBy('title','ASC')->get(),
            'categories'    => Category::select('cat_id', 'cat_title', 'cat_alias')->orderBy('cat_title','ASC')->get(),
            'parent_menus'  => Menu::select('m_id', 'm_title')->orderBy('m_title','ASC')->where('m_id','!=', $id)->get(),
            'menu_order'    => Menu::select('m_title','m_order')->orderBy('m_order','ASC')->get(),
        ];
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
        $plink = $request['plink'];
        $clink = $request['clink'];
        $elink = $request['elink'];
        $rules = [
            'm_title'       => 'required',
            'm_alias'       => 'required|unique:menu,m_alias',
            'm_template'    => 'required',
            'm_type'        => 'required',
            'parent'        => 'required',
            'm_order'       => 'required',
            'm_status'      => ['required', function ($attribute, $value, $fail)  use ($plink, $clink, $elink) {
                if (empty($plink) && empty($clink) && empty($elink)) {
                    return $fail(__('The Links  field is required'));
                }

            }],
        ];
        if (!empty($id)) {
            $rules['m_alias']= 'required|unique:menu,m_alias,'. $id .','.$this->tableId;
        }
        $attribute = [
            'm_title'       => 'Title',
            'm_alias'       => 'Alias',
            'm_template'    => 'Template',
            'm_type'        => 'Menu Type',
            'm_order'       => 'Ordering',
            'Status'        => 'Status',
        ];
        $customMessages = [];

        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //see($request->all());

        $m_view = 'single';

        if( !empty($plink) ){
            $link = $plink;
            Article::where('alias', $plink)->update([ 'is_menu' => 1]);

        }elseif( !empty($clink) ){
            $link = 'section/'.$clink;
            $m_view = 'category';

        }elseif( !empty($elink) ){
            $link = $elink;
            $m_view = 'external';
        }else{
            $link = url();
        }


        $data = [
            'm_title'       => $request['m_title'],
            'm_alias'       => $request['m_alias'],
            'm_type'        => $request['m_type'],
            'parent'        => $request['parent'],
            'm_order'       => $request['m_order'],
            'm_template'    => $request['m_template'],
            'm_status'      => $request['m_status'],
            'm_showcat'     => $request['m_showcat'],
            'm_view'        => $m_view,
            'm_link'        => $link,
        ];

        $where= 'Root';

        if ($request['parent'] !='Root') {
            $where= $request['parent'];
        }

        $orders = $this->model::where('parent',$where)->orderBy('m_order','ASC')->select('m_id','m_order');
        \Cache::forget('menus');
        if ( empty($id) ){

            // Insert Query
            $data['m_date'] =date('Y-m-d H:i:s');
            $orders = $orders->where('m_order','>=',$request['m_order'])->get();
            if (count($orders) >0) {
                foreach ($orders as $key=>$value){
                    $this->model::where($this->tableId, $value->m_id)->update(['m_order'=>$value->m_order+1]);
                }
            }
            $this->model::create($data);

            Logs::create($this->title.' ('.$data['m_title'].')','create');

            return redirect( $this->bUrl)->with('success', 'Record Successfully Created.');

        }else{

            $getData = $this->model::where($this->tableId, $id)->select('m_order')->first();

            if ($getData->m_order !=$request['m_order']) {
                if ($getData->m_order > $request['m_order']) {
                    $uWhere='>=';
                }else{
                    $uWhere='<=';
                }

                $orders = $orders->where('m_id','!=',$id)->where('m_order',$uWhere,$request['m_order'])->get();
                if (count($orders) >0) {
                    foreach ($orders as $key=>$value){
                        if ($getData->m_order > $request['m_order']) {
                            $m_order = $value->m_order+1;
                        }else{
                            $m_order = $value->m_order-1;
                        }
                        $this->model::where($this->tableId, $value->m_id)->update(['m_order'=>$m_order]);
                    }
                }
            }

            $this->model::where($this->tableId, $id)->update($data);

            //remove menu tag from article if any
            $preLinkedArticle = $request['previous_article'];                      
            if( !empty($preLinkedArticle) ) {
                if($preLinkedArticle !== $link && $m_view === 'single')
                    Article::where('alias', $preLinkedArticle)->update([ 'is_menu' => 0]);
            }                

            Logs::create($this->title.' ('.$data['m_title'].')','update');

            return redirect($this->bUrl)->with('success', 'Successfully Updated');
        }
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
            'title'     => 'Delete '.$this->title,
            'pageUrl'   => $this->bUrl.'/delete/'.$id,
            'page_icon' => '<i class="fa fa-book"></i>',
            'objData'   => $this->model::where($this->tableId, $id)->first(),
        ];

        $this->data['tableID'] = $this->tableId;
        $this->data['bUrl'] = $this->bUrl;

        if($request->method() === 'POST' ){
            $this->model::where($this->tableId, $id)->delete();
            Logs::create($this->title.' ('.$this->data['objData']->m_title.')','delete');
            echo json_encode(['fail' => FALSE, 'error_messages' => "Menu item was deleted."]);
        }else{
            $this->layout('delete');
        }

    }
}
