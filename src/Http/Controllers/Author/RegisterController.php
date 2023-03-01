<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Helpers\Logs;
use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Sourcebit\Dprimecms\Models\Roles;
use Sourcebit\Dprimecms\Models\User;
use Illuminate\Http\Request;
use Sourcebit\Dprimecms\Repositories\AuthInterface as Auth;
use Validator;

class RegisterController extends Controller
{
    private $model;
    private $data;
    private $tableId;
    private $bUrl;
    private $auth;
    private $title;

    public function __construct(Auth $auth){

        $this->tableId  = 'cat_id';
        $this->model    = User::class;
        $this->bUrl     = 'author/user';
        $this->title    = 'User';
        $this->auth = $auth;

    }

    public function layout($pageName){
        $this->data['tableID']  = $this->tableId;
        $this->data['bUrl']     = $this->bUrl;
        echo view('sourcebit::author.auth.users.'.$pageName.'', $this->data);
    }

    /* * *
     * create()
     * 
     */

    public function create(){

        $this->data = [
            'title'     => 'Add New '.$this->title,
            'page_icon' => '<i class="fa fa-book"></i>',
            'roles'     => Roles::get()
        ];

        $this->layout('create');
    }


    /* * *
     * store()
     * 
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'full_name' => 'required',
            'role'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed|min:8'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $registerAndActivate = $request->post('create_activate');

        $registerData = [
            'email'     => $request->post('email'),
            'password'  => $request->post('password'),
            'full_name' => $request->post('full_name'),
            'phone'     => $request->post('phone') ?: NULL,
            'status'    => 0,
            'role'      => $request->post('role'),
            'register_n_activate' => $registerAndActivate,
        ];

        $user = $this->auth->register($registerData);

        $role = $this->auth->findRoleByID($request['role'])->name;
        Logs::create($this->title.' (Name : '.$request['full_name'].'; Role : '.$role.')','create');

        if( !$registerAndActivate ){
            // verification code
            $activation = $this->auth->createActivation($user);

            $email = $user->email;

            $message = [
                'email'     => $email,
                'name'      => $request->post('full_name'),
                'code'      => $activation->code,
                'activationUrl' => 'user-activation/'.$user->id.'/'.$activation->code,
            ];

            emailSend($email, ' Verify Email Address', $message, 'email.user_activation');
        }

        successPage( ['title' => 'Success', 'message' => 'User Account Successfully Created.', 'actionText'=> 'Back', 'actionUrl'=> url('author/users') ] );

    }



    /* * *
     * user()
     * 
     */
    public function user( $user_type = '' ){

        $user = $this->auth->getUser();

        $this->data = [
            'title'         => $this->title.' Details',
            'page_icon'     => '<i class="fa fa-book"></i>',
            'objData'       => User::where('id',$user->id)->with('role','role.roleName')->first(),
        ];
        $this->layout('admin_user');
    }

}
