<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Models\Logs;


use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogsController extends Controller
{

	private $data;
	private $bUrl;
    private $tableId;

	public function __construct(){
         $this->tableId = 'log_id';
		 $this->bUrl = 'author/logs';
	}

    public function layout($pageName){
        $this->data['bUrl']     = $this->bUrl;
        echo view('sourcebit::author.logs.'.$pageName.'', $this->data);
    }


	public function index(Request $request){

        $this->data = [
            'title'     => 'Logs',
            'pageUrl'   => $this->bUrl,
            'bUrl'      => $this->bUrl,
            'page_icon' => '<i class="fa fa-book"></i>',
        ];


        //result per page
        $perPage = session('per_page') ?: 20;

        //table item serial starting from 0
        $this->data['serial'] = ( ($request->get('page') ?? 1) -1) * $perPage;


        if($request->method() === 'POST'){
            session(['per_page' => $request->post('per_page') ]);
        }

        //model query...
        $queryData = Logs::with('user')->orderBy( getOrder(Logs::$sortable, 'log_id')['by'], getOrder(Logs::$sortable, 'log_id')['order']);

        //filter by text.....
        if( $request->filled('filter') ){
            $this->data['filter'] = $filter = $request->get('filter');

            $queryData->where('log_title', 'like', '%'.$filter.'%');
            $queryData->orWhere('log_type', 'like', '%'.$filter.'%');
            $queryData->orWhere('log_type', 'like', '%'.$filter.'%');
        }

        $this->data['allData'] =  $queryData->paginate($perPage)->appends( request()->query() ); // paginate


        $this->layout('index');

    }

    public function show($id){
        $id = filter_var($id, FILTER_VALIDATE_INT);
        $objData = Logs::where('log_id', $id)->first();
        if( !$id || empty($objData)){ exit('Bad Request!'); }
        $this->data = [
            'title'         => 'Logs',
            'pageUrl'       => $this->bUrl,
            'bUrl'          => $this->bUrl,
            'page_icon'     => '<i class="fa fa-book"></i>',
            'objData'       => $objData
        ];
        $this->layout('show');
    }

}
