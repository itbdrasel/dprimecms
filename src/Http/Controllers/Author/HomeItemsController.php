<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Helpers\Logs;
use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Sourcebit\Dprimecms\Models\HomeItem;
use Session;
use Illuminate\Http\Request;

use Validator;
class HomeItemsController extends Controller
{

    private $model;
    private $data;
    private $tableId;
    private $bUrl;
    private $title;

    public function __construct(){

        $this->tableId = 'h_id';
        $this->model = HomeItem::class;
        $this->bUrl = 'author/home-items';
        $this->title ='Home Item';

    }

    public function layout($pageName){
        $this->data['tableID'] = $this->tableId;
        $this->data['bUrl'] = $this->bUrl;
        echo view('sourcebit::author.home_items.'.$pageName.'', $this->data);
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
            $queryData->where('h_title', 'like', '%'.$filter.'%');
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
            'h_title'     => 'required',
            'h_order'     => 'required',
            'h_status'    => 'required',
        ];

        $attribute = [
            'h_title'     => 'Title',
            'h_order'     => 'Order',
            'h_status'    => 'Status',
        ];
        $customMessages =[];

        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'h_title'         => $request['h_title'],
            'h_content'       => $request['h_content'],
            'h_order'         => $request['h_order'],
            'h_status'        => $request['h_status'],
        ];
        $url = empty($request['submit'])?url()->previous():$this->bUrl;
        if ( empty($id) ){
            // Insert Query
            $data['h_created_by'] = dAuth()->getUser()->id;
            $this->model::create($data);
            Logs::create($this->title.' ('.$data['h_title'].')','create');
            return redirect( $url)->with('success', 'Record Successfully Created.');
        }else{
            $this->model::where($this->tableId, $id)->update($data);
            Logs::create($this->title.' ('.$data['h_title'].')','update');
            return redirect($url)->with('success', 'Successfully Updated');
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
            Logs::create($this->title.' ('.$this->data['objData']->h_title.')','delete');
            echo json_encode(['fail' => FALSE, 'error_messages' => "Category was deleted."]);
        }else{
            $this->layout('delete');
        }

    }
}
