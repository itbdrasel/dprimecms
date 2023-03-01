<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Helpers\Logs;
use Sourcebit\Dprimecms\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Sourcebit\Dprimecms\Repositories\AuthInterface as Auth;
use Validator;

class ChangePasswordController extends Controller
{
    private $data;

    public function __construct(Auth $auth){
    
        $this->auth = $auth;
    }

    /* * *
     * Change any user password
     *   
     * @return redirection
    */

     public function changePassword(){    

         $this->data = [
             'title'    =>'Change Password',
             'users'    => $this->auth->getAllUser(),
         ];
         return view('sourcebit::author.auth.change_password', $this->data);
     }

    /* * *
     * Change any user password action
     *   
     * @return redirection
    */
    public function changePasswordAction(Request $request){

        $validator = Validator::make($request->all(), [
            'user'      => 'required|integer',
            'password'  => 'required|confirmed|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id = $request['user'];

        $user = $this->auth->findById($id);

        $this->auth->update($user, ['password' => $request['password']]);

        Logs::create('Change Password ('.$user->full_name.')','change');

        return redirect()->back()->with('success', 'Your password has been changed successfully.');
    }


}
