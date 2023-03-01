<?php

namespace Sourcebit\Dprimecms\Http\Controllers\Author;

use Sourcebit\Dprimecms\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Sourcebit\Dprimecms\Services\MediaServices;
use Sourcebit\Dprimecms\Models\Article;
use Storage;
use Auth;

class MediaManagerController extends Controller
{
    //private $model;
    private $data;
    private $tableId;
    private $bUrl;

    public function __construct(){
        $this->bUrl = 'author/mediamanager';
    }

    public function layout($pageName){
        $this->data['bUrl'] = $this->bUrl;
        echo view('sourcebit::author.mediamanager.'.$pageName.'', $this->data);
    }


    public function container(){
        $this->data = [
            'title'       => 'Media Manager',
            'pageUrl'     => $this->bUrl.'/container',
            'page_icon'   => '<i class="fa fa-book"></i>',
        ]; 
        
        $this->layout('container');

    }    

    public function index(Request $request, MediaServices $mediaServices)
    {   
            
        $this->data = [
            'title'         => 'Media Manager',
            'pageUrl'         => $this->bUrl,
            'page_icon'     => '<i class="fa fa-book"></i>',
        ];
                
        $this->data['filter'] = $request->get('filter');

        $path = $request->get('path');

        //dd( $mediaServices->authorizeDirectory($path) );

        //send to the right dir by user
        if( !$mediaServices->verifyPath($path) ){
            $userPath = Auth::userProfile()->directory;        
            return redirect($this->bUrl.'?path='.$userPath);
        }
        
        // check for true path;
        if( $mediaServices->isPath($path) ) $this->data['path'] = $path;
        else abort('404');

        $this->data['breadcrumb'] =  $mediaServices->breadcrumb(request()->path, $this->bUrl);
       
        $this->layout('index');

    }


    public function content_links(Request $request, MediaServices $mediaServices)
    {
        $this->data = [
            'title'         => 'Article Manager',
            'pageUrl'         => $this->bUrl.'/links',
            'page_icon'     => '<i class="fa fa-book"></i>',
        ];

        //result per page
        $perPage = session('per_page') ?: 20;

        //model query...
        $queryData = Article::orderBy( getOrder(Article::$sortable, 'id')['by'], getOrder(Article::$sortable, 'id')['order'])
        ->with('categories');
        $queryData->where('status', '=',  'active');

        //filter by text.....
        if( $request->filled('filter') ){
            $this->data['filter'] = $filter = $request->get('filter');
            $queryData
                ->whereHas('categories.articles', function ($queryData) use ($filter) {
                    $queryData->where('cat_title', 'like',  '%'.$filter.'%');
                })
                ->orWhere('title', 'like', '%'.$filter.'%')
            ;
        }

        $this->data['allData'] =  $queryData->paginate($perPage)->appends( request()->query() ); // paginate
       
        $this->layout('contentlinks');

    }    


    /* ** 
     * create() - Create folder directory.
     * @return 
    */
    public function create(Request $request, MediaServices $mediaservices){        

        $rules = [
            'item' => [ 'required', 'regex:/^[a-zA-Z0-9_-]+$/' ]
        ];
        
        $messages = [
            'item.regex' => 'The :attribute name must be Numbers, Letters or Both.',
        ];

       $validator = Validator::make($request->all(), $rules, $messages);

       if($validator->fails()){
           echo json_encode(['fail' => TRUE, 'messages' => $validator->errors()->first() ]);
       }else{
            $dirName = $request->post('item');
            $path = $request->path;

            try{
                if( $mediaservices->isPath($path) ){
                
                    $mediaservices->createFolder($path.'/'.$dirName);
                    echo json_encode([ 
                        'fail' => FALSE, 'messages' => "Folder Creation Successful"
                    ]); 
                }

            }catch(\Exception $exception){                                        
                echo json_encode([ 'fail' => TRUE, 'messages' => $exception->getMessage() ]);            
            }           
        }
    }




    
    public function upload(Request $request, MediaServices $mediaservices)
    {

        $this->data = [
            'title'     => 'File Uploader',
            'page_icon' => '<i class="fa fa-book"></i>',
            'pageUrl'   => $this->bUrl.'/upload/',
            'bUrl' 		=> $this->bUrl,
        ];

        if($request->method() === 'POST' ){

            $rules = [
                'file' => [
                    'required', 
                    'max:2048',
                    'mimes:jpeg,bmp,png,pdf,docx,txt'
                ],
            ];

            $attributes = [ 'file' => 'file', ];

            $validator = Validator::make($request->all(), $rules, [],  $attributes);

            if($validator->fails()){
                echo json_encode(['fail' => TRUE, 'messages' => $validator->errors()->first() ]);
            }else{                                    
                                
                $fileData = $request->file('file');
                $fileName = $mediaservices->cleanName($fileData->getClientOriginalName(), $fileData->extension());
                
                $mimeType = $fileData->getMimeType();
                                   
                $path = $request->path;
                $path =  $path ? $path.'/' : $path;
                $dirName = Storage::path($path);


                $fileData->move($dirName, $fileName);                

                //processing image file
                $mediaservices->generateThumbnail($path.$fileName, '.tmp/', 90, 55, 50);
                //$mediaservices->generateThumbnail($path.$fileName, '', 200, 150);
                
                echo json_encode(['fail' => FALSE, 'messages' => "File Upload Successful."]);
            }
        }else{
            $this->layout('upload');
//           echo view('sourcebit::author.mediamanager.upload', $this->data);
        }
    }


    public function rename(Request $request, MediaServices $mediaservices){

        $rules = [
            'name' => [ 'required', 'regex:/^[a-zA-Z0-9_-]+$/' ],
            'oldname' => [ 'required' ],        
        ];
        
        $messages = [
            'name.regex' => 'The :attribute name must be Numbers, Letters or Both.',
        ];

       $validator = Validator::make($request->all(), $rules, $messages);

       if($validator->fails()){
           echo json_encode(['fail' => TRUE, 'messages' => $validator->errors()->first() ]);
       }else{
            $newName = $request->post('name');
            $path = $request->path;
            $oldName = $request->oldname;
            $path =  $path ? $path.'/' : $path;

            try{
                if( $mediaservices->isPath($path) ){
                    
                    $mediaservices->rename($path.$newName, $path.$oldName);
                    echo json_encode([ 
                        'fail' => FALSE, 'messages' => "Folder Rename Successful"
                    ]); 
                }

            }catch(\Exception $exception){                                        
                echo json_encode([ 'fail' => TRUE, 'messages' => $exception->getMessage() ]);            
            }           
        }        
    }

    // item delete

    public function delete(Request $request, MediaServices $mediaservices){

        $rules = [
            'name' => [ 'required' ],                
        ];
        
       $validator = Validator::make($request->all(), $rules);

       if($validator->fails()){
           echo json_encode(['fail' => TRUE, 'messages' => $validator->errors()->first() ]);
       }else{
            $name = $request->name;
            $path = $request->path;

            try{
                if( $mediaservices->isPath($path) ){                    
                    $path = $path ? $path.'/' : $path;                    
                    $mediaservices->delete($path.$name);
                    
                    echo json_encode([ 
                        'fail' => FALSE, 'messages' => "The ".$name." was Deleted."
                    ]); 
                }
            }catch(\Exception $exception){                                        
                echo json_encode([ 'fail' => TRUE, 'messages' => $exception->getMessage() ]);            
            }           
        }        
    }





}
