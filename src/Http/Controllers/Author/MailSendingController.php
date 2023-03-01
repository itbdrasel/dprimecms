<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;


use Sourcebit\Dprimecms\Models\Article;
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

class MailSendingController extends Controller
{
    private $model;
	private $moduleName;
	private $data;
	private $tableId;
	private $bUrl;
    private $title;

	public function __construct(Auth $auth){

        $this->model        = EmailModel::class;
        $this->auth         = $auth;
        $this->tableId      = $this->model::$sortable['id'];
        $this->moduleName   = 'author';
        $this->bUrl         = $this->moduleName.'/mail-sending';
        $this->title        = 'EmailModel Sending';
	}


	public function layout($pageName){
		$this->data['tableID'] = $this->tableId;
		$this->data['bUrl'] = $this->bUrl;
        echo view('sourcebit::'.$this->moduleName.'.mail_sending.'.$pageName.'', $this->data);
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
            $queryData->where('mail_subject', 'like', '%'.$filter.'%');
        }

        $this->data['allData'] =  $queryData->paginate($perPage)->appends( request()->query() ); // paginate

        $this->layout('index');

    }

    public function show($id){
        $id = filter_var($id, FILTER_VALIDATE_INT);

        $objData = $this->model::where($this->tableId, $id)->first();
        if( !$id || empty($objData)){ exit('Bad Request!'); }

        $this->data = [
            'title'         => $this->title. 'View',
            'pageUrl'       => $this->bUrl.'/'. $id,
            'page_icon'     => '<i class="fa fa-envelope-open"></i>',
            'objData'       => $objData
        ];
        $this->layout('view');
    }

    public function compose()
    {
        $this->data = [
            'title'         => 'New Message',
            'page_icon'     => '<i class="fa fa-envelope-open"></i>',
            'objData'       => '',
        ];
         $this->data['templates'] = MailTemplate::where('status',1)->get();

        $this->layout('mail_compose');

    }


    public function sendMail(Request $request){

        $rules = [
            'email'         => 'required|email',
            'subject'       => 'required',
        ];

        $attribute = [
            'email'=> 'To'
        ];
        $customMessages =[];
        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->data=[
            'body_message'=> $request['message']
        ];

        emailSend($request['email'], $request['subject'],$this->data,'email.compose_mail');

        $this->createMail($request, $request['email']);
        return redirect( $this->bUrl)->with('success', 'successfully send email');

    }

    public function bulkEmail()
    {
        $this->data = [
            'title'         => 'New Message',
            'page_icon'     => '<i class="fa fa-envelope-open"></i>',
            'objData'       => '',
        ];

        $this->data['templates'] = MailTemplate::where('status',1)->get();

        $this->layout('bulk_email');

    }


    public function bulkSendEmail(Request $request){
	    $article = $request['article'];
        $rules = [
            'subject'       => 'required',
        ];

        if (!empty($article)) {
            if (empty($request['category'])) {
                $rules['email']  = 'required';
            }
        }else{
            $rules['category'] =  'required';
        }
        $attribute = [
            'email'=> 'To'
        ];

        $customMessages =[];
        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->data=[
            'body_message'=> $request['message']
        ];

        $emails = [];
        $mails  = $this->getEmailByCategory($request['category']);

        if (!empty($mails)) {
            if (!empty($mails[0])) {
                foreach ($mails[0] as $key=>$value){
                    $emails[] = $value->email;
                    emailSend($value->email, $request['subject'],$this->data,'email.compose_mail');
                }
            }
            if (!empty($mails[1])) {
                foreach ($mails[1] as $key=>$value){
                    $emails[] = $value->email;
                    emailSend($value->email, $request['subject'],$this->data,'email.compose_mail');
                }
            }

        }
        if (!empty($request['email'])) {
            emailSend($request['email'], $request['subject'],$this->data,'email.compose_mail');
            $emails[] = $request['email'];
        }


        $this->createMail($request, $emails);

        $url = !empty($article)?'author/articles':$this->bUrl;
        return redirect($url)->with('success', 'successfully send bulk email');

    }

    public function getEmailByCategory($category){
        $contact =[];
	    if ($category =='all'){
           $contact = Contact::select('email')->get();
           $newsletter = Newsletter::select('n_email as email')->get();
        }else{
            $newsletter = Newsletter::select('n_email')->where('n_status',$category)->get();
        }
       return [$newsletter, $contact];

    }

    public function getCategoryName($category=''){
        if ($category !='all' && !empty($category)) {
            if ($category ==1) {
                return 'Verified';
            }elseif ($category ==2){
                return 'Whitelist';
            }
            return  'Pending';
        }
        return $category;
    }


    public function createMail($request, $email, $message='', $categoryName=''){
        $data = [
            'mail_address'          => json_encode($email),
            'mail_subject'          => $request['subject'],
            'template_or_message'   => $message,
            'mail_user_id'          => $this->auth->getUser()->id,
            'mail_category'         => !empty($categoryName)?$categoryName:$this->getCategoryName($request['category']),
        ];
        $this->model::create($data);
    }


    public function newsletter(){
        $this->data = [
            'title'         => 'New Newsletter Email',
            'page_icon'     => '<i class="fa fa-envelope-open"></i>',
            'objData'       => '',
        ];

        $this->data['articles'] = Article::get();

        $this->layout('newsletter');
    }

    public function newsletterPreview(Request $request){

        $rules = [
            'subject'       => 'required',
            'articles'      => 'required',
        ];


        $attribute = [

        ];

        $customMessages =[];
        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->data = [
            'title'         => 'Newsletter Mail Preview',
            'page_icon'     => '<i class="fa fa-envelope-open"></i>',
            'objData'       => '',
        ];

        $this->data['subject']  = $request['subject'];
        $this->data['article_id']  = $request['articles'];
        $this->data['email_template'] = view('email.newsletter', $this->data)->render();


        $src = $this->bUrl.'/newsletter-template?subject='.$request['subject'].'&';
        $src .= http_build_query(array('articles' =>  $request['articles']));
        $this->data['src']  =$src;

        $this->layout('newsletter_preview');

    }

    public function newsletterTemplate(Request $request){

        $this->data['articles'] = Article::whereIn('id', $request['articles'])->get();
        $this->data['subject']  = $request['subject'];

        return view('email.newsletter', $this->data);
    }

    public function newsletterSendEmail(Request $request){
        $rules = [
            'subject'       => 'required',
            'articles'      => 'required',
        ];


        $attribute = [

        ];

        $customMessages =[];
        $validator = Validator::make($request->all(), $rules, $customMessages, $attribute);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->data['articles'] = Article::whereIn('id', $request['articles'])->get();
        $this->data['subject']  = $request['subject'];
        $newsletters = Newsletter::select('n_email as email')->where('n_validate',1)->get();

        $emails = [];

        if (!empty($newsletters)) {
            foreach ($newsletters as $key=>$value){
                $emails[] = $value->email;
                emailSend($value->email, $request['subject'],$this->data,'email.newsletter');
            }

        }

        $template = view('email.newsletter', $this->data)->render();
        $this->createMail($request, $emails, $template, 'Newsletter');

        return redirect($this->bUrl)->with('success', 'Successfully send newsletter email');


    }






}
