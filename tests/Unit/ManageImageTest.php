<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Services\ManageImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use File;

use Illuminate\Http\Exceptions\HttpResponseException; 

class ManageImageTest extends TestCase
{
    protected $imgService;


    public function setup(): void
    {
        parent::setUp();

        $this->imgService = new ManageImage();
    }

    public function testCheckImgExists(){

        $img_path = \public_path() . '\img';
        $ext = '.jpg';
        $basename = 'area_5';
        $filepath = $img_path . '\\' . $basename . $ext;

        $this->assertTrue(\file_exists($filepath));
        
        $result =  $this->imgService->checkImgExists($basename,'');
        $expected = 'area_5.jpg';
        $this->assertEquals($expected,$result['relative_path']); 

        $directory = 'roles';
        $ext = '.png';
        $basename = 'doctor';
        $filepath = $img_path . '\\' . $directory . '\\' . $basename . $ext;

        $this->assertTrue(\file_exists($filepath));
        
        $result =  $this->imgService->checkImgExists($basename,$directory);
        $expected = 'roles/doctor.png';
        $this->assertEquals($expected,$result['relative_path']); 

    }

    public function testFailedService(){
        $message = 'test message';

        $response['status']  = 'NG';
        $response['message'] = $message;

        try{
            $result =  $this->imgService->failedService($message);
        }catch (\Throwable $e){
            //とんできたクラスがこのクラスのインスタンスかチェック
            $this->assertInstanceOf(HttpResponseException::class, $e);
            //とんできたエラーメッセージが同じかチェック
            $this->assertJson(\json_encode($response), $e->getMessage());
        }        


    }

    public function testUpload(){
        
        $img_path = \public_path() . '\img';
        $filename = 'sample.jpg';
        $filepath = $img_path . '\\' . $filename;
        $local_path = storage_path() .'/framework/testing/disks/files';


        //  テスト画像をコピー
        File::copy($local_path . '\\' . 'test.jpg' ,$img_path . '\\' . 'test.jpg');  
        File::copy($local_path . '\\' . 'test.jpg' ,$img_path . '\\' . 'sample.jpeg');  

        // Illuminate\Http\UploadedFile
        $upfile = new UploadedFile(
            $img_path . '\\' . 'test.jpg',             // file path
            'sample.jpg',    // original Name
            'image/jpeg',          // mimeType
            filesize( $img_path . '\\' . 'test.jpg' ), // size
            null,                  // error
            true // (2)            // test
        );

        $result =  $this->imgService->upload($filename,'',$upfile);

        // $this->assertEquals($expected,$result); 
        $this->assertTrue(\file_exists($filepath));
        $this->assertFalse(\file_exists($img_path . '\\' . 'sample.jpeg'));

        \unlink($filepath);
    }

    public function testSave(){
        
        $img_path = \public_path() . '\img';
        $filename = 'sample.jpg';
        $filepath = $img_path . '\\' . $filename;
        $local_path = storage_path() .'/framework/testing/disks/files';


        //  テスト画像をコピー
        File::copy($local_path . '\\' . 'test.jpg' ,$img_path . '\\' . 'test.jpg');  

        // Illuminate\Http\UploadedFile
        $upfile = new UploadedFile(
            $img_path . '\\' . 'test.jpg',             // file path
            'sample.jpg',    // original Name
            'image/jpeg',          // mimeType
            filesize( $img_path . '\\' . 'test.jpg' ), // size
            null,                  // error
            true // (2)            // test
        );

        $result =  $this->imgService->save($img_path,$filename,$upfile);

        // $this->assertEquals($expected,$result); 
        $this->assertTrue(\file_exists($filepath));
        \unlink($filepath);

        $filename = 'sample2.jpg';
        $directory = 'roles';
        $filepath = $img_path . '\\' . $directory . '\\' . $filename;

        //  テスト画像をコピー
        File::copy($local_path . '\\' . 'test.jpg' ,$img_path . '\\' . 'test.jpg');

        // Illuminate\Http\UploadedFile
        $upfile = new UploadedFile(
            $img_path . '\\' . 'test.jpg',             // file path
            'sample2.jpg',    // original Name
            'image/jpeg',          // mimeType
            filesize( $img_path . '\\' . 'test.jpg' ), // size
            null,                  // error
            true // (2)            // test
        );

        $result =  $this->imgService->save( $img_path . '\\' . $directory, $filename, $upfile);

        // $this->assertEquals($expected,$result); 
        $this->assertTrue(\file_exists($filepath));
        \unlink($filepath);        

    }

    public function testDelete(){
        $img_path = \public_path() . '\img';
        $filename = 'sample.jpg';
        $filepath = $img_path . '\\' . $filename;
        $local_path = storage_path() .'/framework/testing/disks/files';


        //  テスト画像をコピー
        File::copy($local_path . '\\' . 'test.jpg' ,$img_path . '\\' . $filename); 

        $result =  $this->imgService->delete( $filename, '');

        // $this->assertEquals($expected,$result); 
        $this->assertFalse(\file_exists($filepath));        
    }


    public function testCreatePath(){
        $directory = 'roles';
        $result =  $this->imgService->createPath($directory);

        $expected = $this->imgService->getImgPath() . "/" ."roles";
        $this->assertEquals($expected,$result);    
        
        
        $directory = 'tett';
        $result =  $this->imgService->createPath($directory);
        $this->assertFalse($result); 
        
        $directory = '';
        $result =  $this->imgService->createPath($directory);

        $expected = $this->imgService->getImgPath();
        $this->assertEquals($expected,$result);    
                
    }  


    public function tearDown(): void
    {
        parent::tearDown();
    }     

}
