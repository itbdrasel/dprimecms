<?php


namespace Sourcebit\Dprimecms\Http\Controllers\Author;


use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Sourcebit\Dprimecms\Models\User;
use Illuminate\Http\Request;
//use Sourcebit\Dprimecms\Repositories\AuthInterface as Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends Controller
{

    private $data;
    private $auth;
    public function __construct(){
        $this->auth = dAuth();
    }



    public function layout($pageName){
        $this->data['errors']       = session('errors') ?: new MessageBag;
        echo view('sourcebit::author.auth.'.$pageName.'', $this->data);
    }


    /* * *
     * login()
     *
     */
    public function login(){

        //dd( $this->auth->check() );
        //$user = $this->auth->findById('1');
        //$activation = $this->auth->createActivation($user);

        if($this->auth->check()){
            return $this->auth->roleRedirect();
        }

        $this->data['title'] = 'Administrator - '.config('settings.appTitle');

        $this->layout('login');
    }


    /* * *
     * login_user()
     *
     */

    public function loginAction(Request $request){

        $errorMsg = [
            'email.required' => 'Enter an Email Address.',
            'email.email' => 'Enter an Valid Email Address!',
            'password.required' => 'Enter a Password.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ], $errorMsg);


        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $getCredentials = $request->only('email', 'password');

        $authenticate = $this->auth->authenticate($getCredentials );

        if( !$authenticate->hasFailed() ){

            return $this->auth->roleRedirect();
        }else{

            return redirect()->back()->withErrors('Sorry your email or password is incorrect. Please try again.')->withInput();
        }

    }


    /* * *
     * logout()
     *
     */
    public function logout(){

        $this->auth->logout();

        return redirect('author/login');
    }

    /* * *
     * resetPassword()
     *
     */
    public function resetPassword($code){

        $user = DB::table('password_resets')->where('token', $code)->first();

        if( empty($user) ) {
            return abort(404);
        }elseif( getHours($user->created_at) > 24 ) {
            return abort(404);
        }

        $this->data['title'] = 'Forgot Password';
        $this->data['user'] = $user;

        $this->layout('reset_password');
    }


    /* * *
     * resetPasswordStore()
     *
     */
    public function resetPasswordStore(Request $request, $code){

        $validator = Validator::make($request->all(),[
            'email'     => 'required|email',
            'password'  => 'required|confirmed',
            'code'      => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Provide Valid Information' );
        }

        $userCheck = DB::table('password_resets')->where('token', $code)->first();

        if( empty($userCheck) ) {
            return  SuccessError::errorLogin('Bad Request.','Go to Forgot Password','author/forgot-password');
        }

        $email = $request['email'];
        $user = User::where('email', $email)->first();
        $user = $this->auth->findById($user->id);

        try{
            $this->auth->update($user, ['password' => $request['password']]);

            DB::table('password_resets')->where('token', $code)->delete();

            return  SuccessError::successLogin('Your password has been successfully changed. Use your new password to login.', 'Go to Login', 'author/login');

        }catch(NotUniquePasswordException $e) {
            return redirect()->back()->with('error', 'Bad Request.')->withErrors('Bad Request.')->withInput();
        }

    }




}
