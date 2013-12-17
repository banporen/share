<?php
class UploadController extends ModelController{
		
	public function view(){	        
            $this->display('upload.html'); 
	} 
   
    /**
     * 图片批量上传处理函数
     *
     */  
    public function upload(){      
        if(isset($_FILES["Filedata"]))
        {
            $Filedata = $_FILES["Filedata"];
            if(is_uploaded_file($Filedata["tmp_name"]))
            {
                $info = pathinfo($Filedata['name']);
                $ext  = $info['extension'];
                $key       = md5(Ip::getClientIp().uniqid().$Filedata['name'].time());
                $filename  = $key.'.'.$ext;
                //暂存缓存目录，Imagick处理完转到images目录
                $tmp_file  = PATH_CACHE.'images/'.$filename; 
                if(move_uploaded_file($Filedata['tmp_name'], $tmp_file))
                {
                    $img = new Imagick($tmp_file);  
                    $width = $img->getImageWidth();
                    $height = $img->getImageHeight();
                    //如果是竖图
                    if($height>=$width){
                          if($width>400)
                          {
                              $dest_origin  = './images/show/'.$key.'.'.$ext;
                              if($width>510)
                                    $img->thumbnailImage(510, 0);  
                              $img->writeImage($dest_origin);
                              $dest_face = './images/face/'.$key.'.'.$ext;
                              $img->thumbnailImage(400, 0);
                              $img->cropImage(400 , 326, 0, 0);
                              $img->writeImage($dest_face);    
                              $value = array();
             		          $pictureDB = new PictureModelDB();
             		          $value['original_url'] = ltrim($dest_origin, '.');
             		          $value['face_url'] = ltrim($dest_face, '.');
             		          $value['uid'] = $_POST['uid'];
							  $value['album_id'] = $_POST['album_id'];
             		          $value['title'] = $info['filename'];
             		          $pictureDB->insert($value); 
                          }
                    }
                    //横图
                    else{
                          if($height>326)
                          {
                              $dest_origin  = './images/origin/'.$key.'.'.$ext;
                              if($width>510)
                                    $img->thumbnailImage(510, 0);  
                              $img->writeImage($dest_origin);
                              $dest_face = './images/face/'.$key.'.'.$ext;
                              $img->thumbnailImage(0, 326);
                              $img->cropImage(400 , 326, 0, 0);
                              $img->writeImage($dest_face);                               
             		          $value = array();
             		          $pictureDB = new PictureModelDB();
             		          $value['original_url'] = ltrim($dest_origin, '.');
             		          $value['face_url'] = ltrim($dest_face, '.');
             		          $value['uid'] = $_POST['uid'];
             		          $value['album_id'] = $_POST['album_id'];
						      $value['title'] = $info['filename'];
             		          $pictureDB->insert($value);
			               } 
                    } 
                        
                }
            }
        }         
	}

}
