<?php
/*
* class ManageImage
*/

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException; 

class ManageImage
{
    protected $img_path;

    public function __construct(){
        $this->img_path = \public_path() . '/img';
    }

    /**
     * Upload the file to a destinated location.
     *
     * @param string $filename      The new file name
     * @param string $directory The destination folder
     * @param Illuminate\Http\UploadedFile $file Uploaded file object
     *
     * @return File A File object representing the new file
     */
    public function upload($filename,$directory = '',$file){
        $filepath = $this->createPath($directory);

        if($filepath == false){
            $this->failedUpload("画像アップロードエラー：ディレクトリ${directory}がありません！");
        }

        //既に同じIDのファイルがあれば上書きするため、削除
        $this->delete($filename,$directory);       


        $this->save($filepath,$filename,$file);

        return true;
    }

    public function save($filepath,$filename,$file){
        
        try {

            $file->move($filepath,$filename);
            
        } catch (\Throwable $e) {
            $this->failedService($e->getMessage());
        }

    }

    public function delete($filename,$directory=''){
        $filepath = $this->createPath($directory);

        if($oldfilepath = self::checkImgExists(pathinfo($filename, PATHINFO_FILENAME),$directory)){
            \unlink($filepath . '/' . $oldfilepath['filename']);
        }           
    }

    public function failedService($message){
        // print_r($result['errors']);

        $response['status']  = 'NG';
        $response['message'] = $message;

        throw new HttpResponseException(
            response()->json( $response, 422 )
        );        
    }


    public function createPath($directory = ''){
        if(empty($directory)){
            $filepath = $this->img_path;
        }else{
            if(!\file_exists($this->img_path . "/" . $directory)){
                return false;
            }            
            $filepath = $this->img_path . "/" . $directory;
        }

        return $filepath;
    }

    public function getImgPath(){
        return $this->img_path;
    }

    public static function checkImgExists($name,$directory = ''){

        if(empty($directory)){
            $dir_path = \public_path() . '/img/'; 
        }else{
            $dir_path = \public_path() . '/img/'. $directory . '/'; 
            
        }

        $exts = ['.jpg','.jpeg','.png','.bmp'];

        foreach ($exts as $key => $ext) {
            $filepath = $dir_path . $name . $ext;
            
            if(\file_exists($filepath)){
                if(empty($directory)){
                    return ['relative_path'=>$name . $ext,'filename'=>$name . $ext] ;
                }else{
                    return ['relative_path'=>$directory . '/' . $name . $ext,'filename'=>$name . $ext] ;

                }                
            }
        }

        return false;
        
    }
}