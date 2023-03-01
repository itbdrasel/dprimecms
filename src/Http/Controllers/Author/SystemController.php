<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;
use Illuminate\Support\MessageBag;
use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Sourcebit\Dprimecms\Models\Article;
use Sourcebit\Dprimecms\Models\Category;
use Sourcebit\Dprimecms\Models\Newsletter;
use Sourcebit\Dprimecms\Models\Persistence;
use Sourcebit\Dprimecms\Models\User;
use Carbon\Carbon;
use Sourcebit\Dprimecms\Repositories\AuthInterface as Auth;
//use Illuminate\Support\Facades\Auth;
class SystemController extends Controller
{
    private $data;
    private $auth;
    public function __construct(Auth $auth){
        $this->auth = $auth;
//        $this->middleware('auth');
    }

    public function index(){

        if($this->auth->check()){
            return redirect('/home');
        }
        echo 'Access Denied !';
    }
    
    public function dashboard(){
        $date = Carbon::now()->subDays(7);
        $this->data =[
            'title'             => 'Dashboard',
            'totalArticle'      => Article::where('status','active')->get()->count(),
            'todayArticle'      => Article::where('status','active')->whereDate('created_at', Carbon::today())->get()->count(),
            'articles'          => Article::where('status','active')->select('title','hits')->where(['is_menu'=>'0'])->offset(0)->limit(5)->orderBy('hits','DESC')->get(),
            'sevenDaysArticle'  => Article::where('status','active')->where('created_at', '>=', $date)->get()->count(),
            'categories'        => Category::with('articles')->get(),
            'users'             => User::where('status',1)->get(),
            'onlineUsers'       => Persistence::with('user')->get()->unique('user_id'),
            'newsletters'       => Newsletter::orderBy('created_at', 'DESC')->offset(0)->limit(5)->get(['n_email','created_at']),

            ];

//        dd(view('t'));
        return view('sourcebit::author.dashboard.dashboard',$this->data);
    }

}
