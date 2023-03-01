<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Helpers\Logs;
use Sourcebit\Dprimecms\Models\Newsletter;
use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Session;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;
use Validator;

class NewsletterController extends Controller
{

    private $model;
    private $data;
    private $tableId;
    private $bUrl;
    private $title;

    public function __construct(){

        $this->tableId = 'n_id';
        $this->model = Newsletter::class;
        $this->bUrl = 'author/newsletter';
        $this->title = 'Newsletter';

    }

    public function layout($pageName){
        $this->data['tableID'] = $this->tableId;
        $this->data['bUrl'] = $this->bUrl;
        echo view('sourcebit::author.newsletter.'.$pageName.'', $this->data);
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
            $queryData->where('n_email', 'like', '%'.$filter.'%');
        }

        $this->data['allData'] =  $queryData->paginate($perPage)->appends( request()->query() ); // paginate

        $this->layout('index');

    }

    public function show($id){
        $id = filter_var($id, FILTER_VALIDATE_INT);

        $objData = Newsletter::where('n_id', $id)->first();        
        if( !$id || empty($objData)){ exit('Bad Request!'); }

        $this->data = [
            'title'         => 'Subscriber',
            'pageUrl'       => $this->bUrl,
            'bUrl'          => $this->bUrl,
            'page_icon'     => '<i class="fa fa-email"></i>',
            'objData'       => $objData
        ];

        $this->layout('view');
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

    public function emailSend(Request $request, $id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if( !$id ){ exit('Bad Request!'); }
        $invoice = $this->model::where($this->tableId, $id)->first();
        $this->data = [
            'title'         => $this->title.' E-mail',
            'page_icon'     => '<i class="fa fa-eye"></i>',
            'objData'       => $invoice,
            'subject'       => $request['subject'],
            'full_message'       => $request['message'],
        ];
        $this->data['tableID']  = $this->tableId;
        $this->data['bUrl']     = $this->bUrl;

        if($request->method() === 'POST' ){
            $rules = [
                'email'         => 'required|email',
                'subject'       => 'required',
            ];
            $attribute = [
            ];
            $customMessages =[];
            $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);
            if ($validator->fails()){
                return response()->json($validator->messages(), 200);
            }

            emailSend($request['email'], $request['subject'],$this->data,'email.newsletter_email');
            return 'success';
        }else{

            $this->layout('email');
        }

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
            'n_name'        => 'nullable|string|max:255',
            'n_email'       => 'required|email|max:255',
            'n_validate'    => 'required',
        ];

        $attribute = [
            'n_name'        => 'Name',
            'n_email'       => 'E-mail',
            'n_validate'    => 'Validate',
        ];
        $customMessages =[];

        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'n_name'        => $request['n_name'],
            'n_email'       => $request['n_email'],
            'n_validate'    => $request['n_validate'],
        ];
        if ( empty($id) ){
            $data['n_created_at'] = date('Y-m-d');
            // Insert Query
            $this->model::create($data);
            Logs::create($this->title.' ('.$request['cat_title'].')','create');
            return redirect( $this->bUrl)->with('success', 'Record Successfully Created.');
        }else{
            Logs::create($this->title.' ('.$request['cat_title'].')','update');
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
            echo json_encode(['fail' => FALSE, 'error_messages' => "Newsletter was deleted."]);
        }else{
            $this->layout('delete');
        }

    }
}
