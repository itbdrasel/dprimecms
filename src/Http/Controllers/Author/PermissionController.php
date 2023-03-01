<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Helpers\Logs;
use Sourcebit\Dprimecms\Models\RoleSections;
use Sourcebit\Dprimecms\Roles;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sourcebit\Dprimecms\Repositories\AuthInterface as Auth;

class PermissionController extends Controller
{

	private $data;
	private $bUrl;
    private $auth;

	public function __construct(Auth $auth){

		 $this->tableId = 'roles';
		 $this->bUrl = 'author/permissions';
         $this->auth = $auth;
	}

    public function layout($pageName){

        $this->data['bUrl']     = 'author/permissions';
        $this->data['page_icon'] = '<i class="fa fa-edit"></i>';
        echo view('sourcebit::author.auth.'.$pageName.'', $this->data);
    }

	public function roles(Request $request){
        $this->data = [
            'title'         => 'Role Manage',
            'pageUrl'       => 'author/roles',
            'page_icon'     => '<i class="fa fa-book"></i>',
            'roles'         => DB::table('roles')->get(),
        ];
        $this->layout('roles');
    }

    public function roleCreate(){
        $this->data = [
            'title'         => 'Add New Role',
            'pageUrl'       => 'author/roles',
            'page_icon'     => '<i class="fa fa-book"></i>',
        ];
        $this->layout('role_create');
    }

    public function roleStore(Request $request){

        $rules = [
            'role_name'  =>'required|max:255',
            'role_slug'  =>'required|max:255|unique:roles,slug',
        ];

        $attribute = [];
        $customMessages = [];

        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $this->auth->createRole([
            'name' => $request['role_name'],
            'slug' => $request['role_slug'],
        ]);


        return redirect('author/roles')->with('success', 'Record Successfully Created.');
    }


    public function permissions(Request $request){

        $this->data = [
            'title'     => 'User Role Manager',
            'pageUrl'   => $this->bUrl,
			'page_icon' => '<i class="fa fa-book"></i>',
            'sections'  => RoleSections::get(),
        ];

        $this->data['user']  = $this->auth->getUser();
		$this->data['sectionNames'] = collect();
		$this->data['rolePermissions'] = collect();

		if($request->method() === 'POST' ){

			$rules = [
				'role'	=> ['required'],
			];
			$validator = Validator::make($request->all(), $rules);

			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}else{

				$role = $this->data['roleId'] = $request->post('role');

				$section = $this->data['sectionId'] = $request->post('section');

				$permissions = $this->data['rolePermissions'] = $this->auth->findRoleById($role);

				//dd($permissions->permissions);
                $query = DB::table('tbl_module_sections');
                if ($section) {
                    $query->where('section_id',$section);
                }
				$sectionNames = $this->data['sectionNames'] = $query->get();
				//dd($sectionNames);
			}
		}

        $this->layout('permissions');

    }


    public function assign(Request $request){

        $role = $this->auth->findRoleByName($request->role);
        $action = $request->action;
        $value = ($request->value == 1) ? true : false;
		if($role){
			$role->removePermission($action,$value)->save();
        	$role->addPermission($action,$value)->save();
            Logs::create('Permission (Role : '.$role->name.'; Route Name : '.$action.')',($request->value == 1)?'Enable':'Disable');
		}else{
			return false;
		}

    }

    public function userPermission(Request $request){
        $user = $this->auth->findUserById($request['id']);
        $action = $request->action;
        $value = ($request->value == 1) ? true : false;
        if($user){
            $user->removePermission($action,$value)->save();
            $user->addPermission($action,$value)->save();
            Logs::create('User Permission (Name : '.$user->full_name.'; Route Name : '.$action.')',($request->value == 1)?'Enable':'Disable');
        }else{
            return false;
        }

    }

	// register new routes to the system with the role permission.
    public function routeRegister(Request $request){

        $this->data = [
            'title'     => 'Register New Route',
            'pageUrl'   => $this->bUrl,
			'page_icon' => '<i class="fa fa-book"></i>',
			'roles' => Roles::get(),
        ];

        $this->data['user']  = $this->auth->getUser();

		if($request->method() === 'POST' ){

			$rules = [
				'section_name'	=> ['required'],
				'route_name'	=> ['required',
					function($attribute, $value, $fail){
						if ( !Route::has($value) ) {
							$fail('The '.$attribute.' is not exist.');
						}
					}
				],
				'role'			=> ['required'],
			];
			$validator = Validator::make($request->all(), $rules);

			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}else{

				$sectionName = $request->post('section_name');
				$routeName = $request->post('route_name');


				$roles = $request->post('role'); // can be multiple [array]

				$routeWithRoles  = json_encode( [$routeName => $roles ] );

				// check if the section name exist
				if (DB::table('tbl_module_sections')->where('section_name', $sectionName)->exists()) {

					$getRoutes = DB::table('tbl_module_sections')->where([ 'section_name' => $sectionName] )->value('section_action_route');

					$routeNames = json_decode($getRoutes, true);

					if(array_key_exists($routeName, $routeNames) ){

                        $routeNames[$routeName] = $roles; // update existing route.

					}else{
						$routeNames += [$routeName => $roles ]; // append new route for existing section

					}
					$routeWithRoles = $routeNames;
				}else{
                    $roles_permission =trim($this->arrayValue($roles),',"');
                    $routeData['section_roles_permission']  = '["'.$roles_permission.'"]';
                }

				$routeData['section_name']          = $sectionName;
				$routeData['section_action_route']  = $routeWithRoles;



				DB::table('tbl_module_sections')->updateOrInsert( ['section_name' => $sectionName], $routeData );

				return redirect()->back()->with('success', 'Data Recorded Successful.');
			}
		}
        $this->layout('route_register');
    }

    public function routeRegisterEdit(Request $request){

        $this->data = [
            'title'     => 'Register Edit Route',
            'pageUrl'   => $this->bUrl.'_edit',
            'page_icon' => '<i class="fa fa-book"></i>',
            'sections'  => RoleSections::get(),
            'roles'     => Roles::get(),
        ];

        $this->data['user']         = $this->auth->getUser();
        $this->data['sections']     = DB::table('tbl_module_sections')->get();
        $this->data['route'] ='';
        $this->data['sectionId'] ='';
        if($request->method() == 'GET' ){
            $section_id = $request['section'];
            $this->data['sectionId'] = $section_id;
            $this->data['route']        = DB::table('tbl_module_sections')->where('section_id',$section_id)->first();
        }


        if($request->method() === 'POST' ){
            $id = $request['section_id'];
            $sectionName = $request->post('section_name');
            $routes_name = $request['route_name'];

            $routeNames =[];
            $sectionRoute=[];

            foreach ($routes_name as $key=>$value){

                $roles = (array_key_exists ($key , $request['roles']))?$request['roles'][$key]:[''];

                if (Route::has($value) ) {
                    $routeNames[$value] = $roles;
                    $sectionRoute[]=$roles;

                }

            }
            $roles_permission =trim($this->arrayValue($sectionRoute),',"');

            $routeData = [
                'section_name' => $sectionName,
                'section_action_route' => json_encode($routeNames),
                'section_roles_permission' => '["'.$roles_permission.'"]',
            ];
            $getRoutes = DB::table('tbl_module_sections')->select('section_id')->where([ 'section_name' => $sectionName] )->first();
            if (!empty($getRoutes) &&  $getRoutes->section_id !=$id) {
                DB::table('tbl_module_sections')->where([ 'section_id' => $id] )->delete();
                DB::table('tbl_module_sections')->where('section_name',$sectionName)->update($routeData);

                return redirect('system/core/permissions')->with('success', 'Data Successful Update.');
            }else{
                DB::table('tbl_module_sections')->where([ 'section_id' => $id] )->update($routeData);
            }

            return redirect()->back()->with('success', 'Data Successful Update.');
        }
        $this->layout('route_register_edit');
    }
    function array_flatten($array) {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            }
            else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    function arrayValue($array){
        $data = $this->array_flatten($array);
        $data = array_unique(array_values($data));
        $result = '';
        foreach ($data as $key => $value) {
            $result .= '"'.(int)$value.'",';
        }
        return $result;
    }

    // Ajax Json Query
    function routeRemove(Request $request){
	    $id     = $request['id'];
	    $route  = $request['route'];
        $route_date = DB::table('tbl_module_sections')->where('section_id',$id)->first();
        $actions = json_decode($route_date->section_action_route);
        if (!empty($actions->$route)) {
            unset($actions->$route);
        }

        DB::table('tbl_module_sections')->where('section_id',$id)->update(['section_action_route'=>json_encode($actions)]);
    }

    function routeCheck(Request $request){
        $route_name     = $request['route_name'];
        $error = true;
        if (!Route::has($route_name)) {
            $error = false;
            $message = 'The '.$route_name.' is not exist.';
        }
        return json_encode(['error'=>$error, 'message'=>$message]);

    }

}
