<?php

namespace Sourcebit\Dprimecms\Services;

use Exception;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Storage;
use Auth;


class MediaServices{

    public function __construct(){

    }

    /***
     * Check whether the path is valid or not
     * Null path assume as the root.
     **/
    public function isPath($path)
    {
        if($path) return Storage::exists($path); // true or false
        elseif($path === null) return true; // assume the root
        else return false;

    }


    /***
     * Check whether the user is authorized to access the directory
     * @param String $path - requested path
     *
     * @return Boolean.
     **/
    public function verifyPath($path)
    {
        if($path) $userBasePath = explode('/', urldecode($path));

        $profileDir = Auth::userProfile()->directory;
        $role = Auth::getUser()->roles[0]->slug;

        if($role === 'admin'){
            //admin access anywhere.
            return true;
        }elseif($profileDir === $userBasePath[0]){
            return true;

        }else return false;

    }



    /***
     * sanitise directory name
     **/
    public function cleanName($fileName, $fileExt): string
    {
       return  Str::slug(pathinfo($fileName, PATHINFO_FILENAME)).'.'.$fileExt;
    }


    /***
     * create folder directory
     **/
    public function createFolder($folderName)
    {
        if( !Storage::exists($folderName) ){
            $makeDir = Storage::makeDirectory($folderName);

            if( !$makeDir ){
                throw new Exception('Directory not created for '.$folderName.' name.');
            }
        }else{
            throw new Exception('The directory '.$folderName.' is already exist.');
        }
        return $makeDir;
    }


    public function copyFile(){
        //
    }

    //
    public function moveFile(){

    }

    /***
     * Delete file or directory
     **/
    public function delete($namePath){
        //dd($namePath);
        if( Storage::exists($namePath) ){

            if(is_file(Storage::path($namePath))) {

                //for image file delete from temp first
                if($this->isImage($namePath)) Storage::delete('.tmp/'.base64_encode($namePath));

                $delete = Storage::delete($namePath);

            }else $delete = Storage::deleteDirectory( $namePath);

            if( !$delete ) throw new Exception('This item '.$namePath.' can not be deleted.');
        }else{
            throw new Exception('The directory '.$namePath.' is not exist.');
        }
        return $delete;
    }


    /***
     * Rename file or directory
     **/
    public function rename($newName, $oldName){

        if( Storage::exists($oldName) ){

            if(is_file(Storage::path($oldName))){
                $extension = pathinfo($oldName, PATHINFO_EXTENSION);
                $reName = Storage::move($oldName, $newName.'.'.$extension);

                //manage thumbnail
                if($this->isImage($newName.'.'.$extension)){
                    $this->removeThumbnail($oldName, '.tmp/');
                    $this->generateThumbnail($newName.'.'.$extension, '.tmp/', 80, 50);
                }

            }else{
                $reName = Storage::move($oldName, $newName);
            }

            if( !$reName ){
                throw new Exception('Directory not created for '.$newName.' name.');
            }
        }else{
            throw new Exception('The directory '.$oldName.' is not exist.');
        }
        return $reName;
    }


    /***
     * Media Manager Breadcrumb
     **/
    public function breadcrumb($path, $baseUrl):string
    {
        $newPath = '';
        $folders = [];

        if($path) $folders = array_filter(explode('/', $path), 'strlen');

        $breadcrumb = "<li><a href='".url($baseUrl)."'><span class='fa fa-home'></span> </a> </a></li>";
        $counter = 1;
        foreach($folders as $dir){

           if($counter == count($folders) ){
            $newPath .= $dir;
            $breadcrumb .= "<li> &rarr; ".$dir."</li>";
           }else{
            $newPath .= $dir.'/';
            $breadcrumb .= "<li> &rarr; <a href='".url($baseUrl)."?path=".urlencode($newPath)."'>".$dir."</a> </li>";
           }
           $counter++;
        }

        return $breadcrumb = "<ul>".$breadcrumb."</ul>";
    }

    /* * *
    * isImage() - check the file is image or not.
    * @param filepath.
    * @return Boolean
    */

    public function isImage($namePath){

        $mimeType = mime_content_type(Storage::path($namePath));

        if(substr($mimeType, 0, 5) === 'image') return true;
        else return false;
    }


    /* * *
     * generateThumbnail()
     * @param $sourcFile with file path and name
     * @param $desPath without file name
     */

    public function generateThumbnail($sourcFile, $desPath, $width, $height, $quality = ''){

        $pathInfo = pathinfo($sourcFile);
        $quality = $quality ? $quality : 100;

        if($desPath == '.tmp/'){
            //tmp not exist, create one.
            if(!Storage::exists($desPath)) Storage::makeDirectory($desPath);

            $fileName = base64_encode($pathInfo['filename']).'.'.$pathInfo['extension'];

        }else $fileName = $pathInfo['filename'].'-'.$width.'x'.$height.'.'.$pathInfo['extension'];

        if($this->isImage($sourcFile)) {
            $imageData = Image::make(Storage::path($sourcFile))->resize($width, $height);
            $imageData->save(Storage::path($desPath.$fileName), $quality);
        }
    }


     /* * *
     * removeThumbnail()
     * @param $sourcFile with file path and name
     * @param $dir without file name
     */

    public function removeThumbnail($filePath, $dir){

        //$pathInfo = pathinfo($filePath);

        if($dir == '.tmp/') $filePath = '.tmp/'.base64_encode($filePath);
        else $filePath = $dir.$filePath;

        //if($this->isImage($filePath)) {
            Storage::delete($filePath);
        //}
    }




}
