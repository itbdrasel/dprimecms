<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Helpers\Logs;
use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Sourcebit\Dprimecms\Models\RoleUser;
use Sourcebit\Dprimecms\Models\Roles;
use Sourcebit\Dprimecms\Models\User;
use Sourcebit\Dprimecms\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sourcebit\Dprimecms\Repositories\AuthInterface as Auth;
use Validator;

class UsersController extends Controller
{

    private $model;
    private $data;
    private $bUrl;
    private $title;
    public function __construct(Auth $auth){

        $this->model    = User::class;
        $this->bUrl     = 'author/user';
        $this->auth     = $auth;
        $this->title    = 'User';
    }

    public function layout($pageName){
        $this->data['bUrl']  = $this->bUrl;
        echo view('sourcebit::author.auth.users.'.$pageName.'', $this->data);
    }


    public function index(Request $request){
        $this->data = [
            'title'         => $this->title.' Manager',
            'pageUrl'         => $this->bUrl,
            'page_icon'     => '<i class="fa fa-book"></i>',
        ];

        // result per page

        $this->data['roles'] = Roles::get();

        $this->data['selected'] = $request['selected'];

        $perPage = session('per_page') ?: 20;

        //table item serial starting from 0
        $this->data['serial'] = ( ($request->get('page') ?? 1) -1) * $perPage;


        if($request->method() === 'POST'){
            session(['per_page' => $request->post('per_page') ]);
        }


        // model query...
        $queryData = $this->model::
            leftJoin('role_users', 'users.id', 'role_users.user_id')
            ->leftJoin('roles', 'role_users.role_id', 'roles.id')
            ->leftJoin('activations', 'activations.user_id', 'users.id')
            ->selectRaw('users.*, roles.name, activations.completed')
            // Group by...
            ->orderBy( getOrder($this->model::$sortable, 'users.id')['by'], getOrder($this->model::$sortable, 'users.id')['order'])
        ;
        

        if( $request->filled('filter') ) {
            $this->data['filter'] = $filter = $request->get('filter');
            $queryData->where(function ($query) use ($filter) {
                $query->orWhere('full_name', 'like', $filter);
                $query->orWhere('email', 'like', $filter);
            });
        }

        //filter by User Role.....
        if( $request->filled('selected') ){
            $this->data['selected'] = $selectedFilter = $request->get('selected');
            $queryData->where( [ 'roles.id' => $selectedFilter ] );
        }

        $this->data['allData'] =  $queryData->paginate($perPage)->appends( request()->query() ); // paginate

        $this->layout('index');


    }

   /* * *
     * edit()
     * 
     */
	public function edit($id){

        if( !$this->auth->userExist($id) ) abort('404');            

        $this->data = [
            'title'         => 'Edit '.$this->title,
            'bUrl'          => $this->bUrl.'/update',
            'objData'       => User::where('id',$id)->with('role')->first(),
            'roles'         => Roles::get()
        ];
        $this->layout('edit');
//        return view('author.auth.users.edit',$this->data);

    }

    /* * *
     * update()
     * 
     */
    public function update(Request $request){

        $id = $request['id'];
        
        if( !$this->auth->userExist($id) ) abort('404');

        $rules = [
            'full_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|integer',
            'status' => 'required|gt:-1|lt:2'
        ];

        $attribute = [
            'full_name' => 'User Name',
        ];

        $customMessages = [];

        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);

        if ($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        $params = [
            'full_name' => $request['full_name'],
            'status'    => $request['status'],
        ];
        RoleUser::where('user_id', $id)->update([ 'role_id' => $request['role'] ]);

        //$this->model::where('id', $id)->update($params);

        $user = $this->auth->findById($id);
        $this->auth->update($user, $params);

        $this->auth->activateDeactivate($id, $request['status']);


        // Profile Update
        $profile =  [
            'full_name' => $request['full_name'],
        ];
        
        
        Profile::where('p_uid', $id)->update($profile);
        
        $role = $this->auth->findRoleById($request['role'])->name;
        $oldRole = $this->auth->findRoleById($request['p_role'])->name;

        Logs::create($this->title.' (Name : '.$request['full_name'].'; Role : '.$role.' Previous Role : '.$oldRole.')','update');

        return 'success';
    }

    public function show($id){
        $this->data = [
            'title'         => $this->title.' Show',
            'page_icon'     => '<i class="fa fa-book"></i>',
            'objData'       => $this->model::where('id',$id)->with('role','role.roleName')->first(),
        ];
        $this->layout('show');
    }

    /* * *
     * destroy()
     * 
     */
    public function destroy(Request $request, $id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if( !$id ){ exit('Bad Request!'); }

        if( !$this->auth->userExist($id) ) abort('404');

        $this->data = [
            'title'     => 'Delete '.$this->title,
            'pageUrl'   => $this->bUrl.'/delete/'.$id,
            'objData'   => User::where('id',$id)->first(),
        ];

        $this->data['bUrl'] = $this->bUrl.'/delete/'.$id;

        if($request->method() === 'POST' ){
            User::where('id',$id)->delete();
            Logs::create($this->title.' ('.$this->title['objData']->full_name.')','delete');
            return json_encode(['fail' => FALSE, 'error_messages' => " was deleted."]);
        }else{
            $this->layout('delete');
//            return view('author.auth.users.delete',$this->data);
        }

    }    

    public function profile($id, Request $request){

        $user = $this->model::where('users.id', $id)
            ->leftJoin('role_users', 'users.id', 'role_users.user_id')
            ->leftJoin('roles', 'role_users.role_id', 'roles.id')
            ->selectRaw('users.*, roles.name, roles.id as role_id')->first();

        $this->data = [
            'title'         => $this->title.' Profile',
            'page_icon'     => '<i class="fa fa-book"></i>',
            'objData'       => $user,
            'permission'    => $request['permission']
        ];

        $this->data['sectionNames'] = DB::table('tbl_module_sections')->get();
        $this->layout('profile');
    }
}
