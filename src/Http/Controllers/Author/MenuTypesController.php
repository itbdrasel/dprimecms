<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Helpers\Logs;
use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Sourcebit\Dprimecms\Models\MenuType;

use Session;
use Illuminate\Http\Request;
use Validator;

class MenuTypesController extends Controller
{

    private $model;
    private $data;
    private $tableId;
    private $bUrl;
    private $title;

    public function __construct(){

        $this->tableId = 'mt_id';
        $this->model = MenuType::class;
        $this->bUrl = 'author/menu-types';
        $this->title = 'Menu Type';

    }

    public function layout($pageName){
        $this->data['tableID'] = $this->tableId;
        $this->data['bUrl'] = $this->bUrl;
        echo view('sourcebit::author.menu_types.'.$pageName.'', $this->data);
    }


    public function index(Request $request)
    {

        $this->data = [
            'title'         => $this->title.' Manager',
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
            $queryData->where('mt_title', 'like', '%'.$filter.'%');
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
            'mt_title'  => 'required',
            'mt_alias'  => 'required|unique:menu_type,mt_alias',
            'mt_tmpl'   => 'required',
        ];
        if (!empty($id)) {
            $rules['mt_alias']= 'required|unique:menu_type,mt_alias,'. $id .','.$this->tableId;
        }
        $attribute = [
            'mt_title'  => 'Title',
            'mt_alias'  => 'Alias',
            'mt_tmpl'   => 'Menu Template/Place',
        ];
        $customMessages =[];

        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'mt_title'  => $request['mt_title'],
            'mt_alias'  => $request['mt_alias'],
            'mt_tmpl'   => $request['mt_tmpl'],
        ];
        if ( empty($id) ){
            // Insert Query
            $data['mt_date'] =date('Y-m-d H:i:s');
            $this->model::create($data);
            Logs::create($this->title.' ('.$request['mt_title'].')','create');
            return redirect( $this->bUrl)->with('success', 'Record Successfully Created.');
        }else{
            $this->model::where($this->tableId, $id)->update($data);
            Logs::create($this->title.' ('.$request['mt_title'].')','update');
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
            Logs::create($this->title.' ('.$this->data['objData']->mt_title.')','delete');
            echo json_encode(['fail' => FALSE, 'error_messages' => "Category was deleted."]);
        }else{
            $this->layout('delete');
//            return view('author.menu_types.delete', $this->data);
        }

    }
}
