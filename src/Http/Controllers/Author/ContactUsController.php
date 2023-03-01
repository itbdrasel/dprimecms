<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;


use Sourcebit\Dprimecms\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;
use Sourcebit\Dprimecms\Helpers\Logs;
use Sentinel;

class ContactUsController extends Controller
{
    private $model;
	private $moduleName;
	private $data;
	private $tableId;
	private $bUrl;
    private $title;

	public function __construct(){

        $this->tableId = 'id';
        $this->model = Contact::class;
        $this->moduleName = 'author';
        $this->bUrl = $this->moduleName.'/contact-us';
        $this->title = 'Contact Us';
	}


	public function layout($pageName){
		$this->data['tableID'] = $this->tableId;
		$this->data['bUrl'] = $this->bUrl;
        echo view('sourcebit::'.$this->moduleName.'.contact_us.'.$pageName.'', $this->data);
	}

    public function index(Request $request){

        $this->data = [
            'title'         => $this->title.' Manager',
			'pageUrl'       => $this->bUrl,
            'page_icon'     => '<i class="fa fa-book"></i>',
        ];
        $this->data ['parents'] = $this->model::get();
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
			$queryData->where('name', 'like','%'.$filter.'%');
			$queryData->orWhere('phone', 'like','%'.$filter.'%');
			$queryData->orWhere('email', 'like','%'.$filter.'%');
			$queryData->orWhere('subject', 'like','%'.$filter.'%');
		}

		$this->data['allData'] =  $queryData->paginate($perPage)->appends( request()->query() ); // paginate
		$this->layout('index');

    }


    public function show($id){
        $id = filter_var($id, FILTER_VALIDATE_INT);

        $objData = $this->model::where('id', $id)->first();
        if( !$id){ exit('Bad Request!'); }
        if (empty($objData)) {
         return redirect($this->bUrl);
        }

        $this->data = [
            'title'         => $this->title,
            'pageUrl'       => $this->bUrl,
            'bUrl'          => $this->bUrl,
            'page_icon'     => '<i class="fa fa-email"></i>',
            'objData'       => $objData
        ];
        $this->layout('view');
    }

    public function emailSend(Request $request, $id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if( !$id ){ exit('Bad Request!'); }
        $objData = $this->model::where($this->tableId, $id)->first();
        $this->data = [
            'title'         => $this->title.' E-mail',
            'page_icon'     => '<i class="fa fa-eye"></i>',
            'objData'       => $objData,
            'subject'       => $request['subject'],
            'full_message'  => $request['message'],
            'pageUrl'       => $this->bUrl.'/email-send/'.$id,
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

            emailSend($request['email'], $request['subject'],$this->data,'email.contact_us_email');
            return 'success';
        }else{

            $this->layout('email');
        }

    }


    public function destroy(Request $request, $id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if( !$id ){ exit('Bad Request!'); }

        $this->data = [
            'title'     => 'Delete '.$this->title,
            'pageUrl'   => $this->bUrl.'/delete/'.$id,
            'objData'   => $this->model::where($this->tableId,$id)->first(),
        ];

        $this->data['bUrl'] = $this->bUrl.'/delete/'.$id;

        if($request->method() === 'POST' ){

            $title = $this->data['objData']->at_name;

            $this->model::where($this->tableId,$id)->delete();

            $log_title = $this->title.' (Name -'.$title .')  was deleted by '. Sentinel::getUser()->full_name;
            Logs::create($log_title,str_replace(' ','_',strtolower($this->title)).'_delete');

            return json_encode(['fail' => FALSE, 'error_messages' => " was deleted."]);
        }else{
            $this->layout('delete');
        }

    }

}
