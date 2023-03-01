<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;


use Sourcebit\Dprimecms\Models\Contact;
use Sourcebit\Dprimecms\Models\EmailModel;
use Sourcebit\Dprimecms\Models\MailTemplate;
use Sourcebit\Dprimecms\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use phpDocumentor\Reflection\Types\Null_;
use Validator;
use Sourcebit\Dprimecms\Helpers\Logs;


use Sourcebit\Dprimecms\Repositories\AuthInterface as Auth;

class MailTemplateController extends Controller
{
    private $model;
	private $moduleName;
	private $data;
	private $tableId;
	private $bUrl;
    private $title;

	public function __construct(Auth $auth){

        $this->model        = MailTemplate::class;
        $this->auth         = $auth;
        $this->tableId      = $this->model::$sortable['id'];
        $this->moduleName   = 'author';
        $this->bUrl         = $this->moduleName.'/mail-template';
        $this->title        = 'Template';
	}


	public function layout($pageName){
		$this->data['tableID'] = $this->tableId;
		$this->data['bUrl'] = $this->bUrl;
        echo view('sourcebit::'.$this->moduleName.'.mail_template.'.$pageName.'', $this->data);
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
            $queryData->where('name', 'like', '%'.$filter.'%');
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
            'title'         => 'Add New '. $this->title,
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
            'name'          => 'required',
            'template'      => 'required',
            'status'        => 'required',
        ];

        $attribute = [

        ];
        $customMessages =[];

        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'name'      => $request['name'],
            'template'  => $request['template'],
            'status'    => $request['status'],
        ];
        if ( empty($id) ){
            // Insert Query
            $this->model::create($data);
            Logs::create($this->title.' ('.$request['name'].')','create');

            return redirect( $this->bUrl)->with('success', 'Record Successfully Created.');
        }else{
            $this->model::where($this->tableId, $id)->update($data);
            Logs::create($this->title.' ('.$request['name'].')','updated');
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
            Logs::create($this->title.' ('.$this->data['objData']->name.')','delete');
            echo json_encode(['fail' => FALSE, 'error_messages' => "Category was deleted."]);
        }else{
            $this->layout('delete');
        }

    }





}
