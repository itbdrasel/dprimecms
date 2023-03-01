<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Helpers\Logs;
use Sourcebit\Dprimecms\Models\Category;
use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Session;
use Illuminate\Http\Request;
use Validator;

class CategoriesController extends Controller
{

    private $model;
    private $data;
    private $tableId;
    private $bUrl;
    private $title;

    public function __construct(){

        $this->tableId = 'cat_id';
        $this->model = Category::class;
        $this->bUrl = 'author/category';
        $this->title = 'Category';

    }

    public function layout($pageName){
        $this->data['tableID'] = $this->tableId;
        $this->data['bUrl'] = $this->bUrl;
        echo view('sourcebit::author.categories.'.$pageName.'', $this->data);
    }


    public function index(Request $request)
    {

        $this->data = [
            'title'         => $this->title.' Management',
            'pageUrl'         => $this->bUrl,
            'page_icon'     => '<i class="fa fa-book"></i>',
        ];

        //result per page
        $perPage = session('per_page') ?: 20;

        //table item serial starting from 0
        $this->data['serial'] = ( ($request->get('page') ?? 1) -1) * $perPage;


        if($request->method() === 'POST'){
            session(['per_page' => $request->post('per_page') ]);
        }
        //model query...
        $queryData = $this->model::orderBy( getOrder($this->model::$sortable, $this->tableId)['by'], getOrder($this->model::$sortable, $this->tableId)['order']);
        //filter by text.....
        if( $request->filled('filter') ){
            $this->data['filter'] = $filter = $request->get('filter');
            $queryData->where('cat_title', 'like', '%'.$filter.'%');
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
            'title'         => 'Add New '.$this->title,
            'page_icon'     => '<i class="fa fa-book"></i>',
            'objData'       => '',
            'parents'       => $this->model::where('cat_parent', 'root')->get()
        ];
        $this->layout('create');
    }
    public function edit($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if( !$id ){ exit('Bad Request!'); }

        $this->data = [
            'title'         => 'Edit '.$this->title,
            'page_icon'     => '<i class="fa fa-book"></i>',
            'objData'       => $this->model::where($this->tableId, $id)->first(),
            'parents'       => $this->model::where('cat_parent', 'root')->get()
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
        $rules = [
            'cat_title'     => 'required|string|max:30',
            'cat_alias'     => 'required|string|max:30|unique:category,cat_alias',
            'cat_parent'    => 'required',
            'cat_status'    => 'required|string|max:8',
        ];
        if (!empty($id)) {
            $rules['cat_alias']= 'required|unique:category,cat_alias,'. $id .','.$this->tableId;
        }
        $attribute = [
            'cat_title'     => 'Category Title',
            'cat_alias'     => 'Category Alias',
            'cat_parent'    => 'Category Parent',
            'cat_status'    => 'Status',
        ];
        $customMessages =[];

        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'cat_title'         => $request['cat_title'],
            'cat_alias'         => $request['cat_alias'],
            'cat_parent'        => $request['cat_parent'],
            'cat_description'   => $request['cat_description'],
            'cat_status'        => $request['cat_status'],
        ];
        if ( empty($id) ){
            // Insert Query
            $this->model::create($data);
            Logs::create($this->title.' ('.$request['cat_title'].')','create');
            return redirect( $this->bUrl)->with('success', 'Record Successfully Created.');
        }else{
            $this->model::where($this->tableId, $id)->update($data);
            Logs::create($this->title.' ('.$request['cat_title'].')','updated');
            $this->model::where($this->tableId, $id)->update($data);
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
            Logs::create($this->title.' ('.$this->data['objData']->cat_title.')','delete');
            echo json_encode(['fail' => FALSE, 'error_messages' => "Category was deleted."]);
        }else{
            return view('sourcebit::author.categories.delete', $this->data);
        }

    }
}
