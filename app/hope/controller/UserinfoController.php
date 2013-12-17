<?php
class UserinfoController extends ModelController{
		
	public function login(){	       
            $reffer = $_SERVER['HTTP_REFERER'];
            $this->setView('tab', 'login');
            $this->setView('reffer', $reffer); 
            $this->display('login.html'); 
	} 
  
    public function islogin(){
        $flag = $this->islogin();
        if($flag)
            Message::showSucc('ok');
        else
            Message::showError('error');
    }
  
    public function regist(){
            
            $this->display('regist.html');
    }

    public function avartar(){
        $this->display('avartar.html');
    }

    public function infoset(){
        $this->display('update.html');
    }

    public function updateUserinfo(){
        $email = Session::get('email');
        if($email){
            $update = array();
            $where = array();
            $userinfoDB = new UserinfoModelDB();
            $username =  trim($_POST['username']);
            if($username!='')
                $update['username'] = $username;
            $update['desc'] = trim($_POST['desc']); 
            $where['email'] = $email;
            $userinfoDB->update($update, $where);
        }
        $this->redirectTo('/userinfo/infoset');
    }

    public function password(){
        $this->display('password.html');
    }

    public function updatePassword(){
        $email = Session::get('email');
        if($email){
            $update = array();
            $where = array();
            $userinfoDB = new UserinfoModelDB();
            $update['password'] = md5($_POST['password']);
            $where['email'] = $email; 
            $userinfoDB->update($update, $where);
        }
        $this->redirectTo('/userinfo/password');
    }

    public function checkPass(){
        $email = Session::get('email');
        if($email){
            $userinfoDB = new UserinfoModelDB();
            $userinfo = $userinfoDB->getUserinfoByEmail($email);
            $password = md5($_POST['password']);
            if($password==$userinfo['password']){
                Message::showSucc('ok');
            }else{
                Message::showError('error');
            }
        }
    }

    /*
     *  销毁Session
     *
     */
    public function logout(){
        Session::destroy();
        
        $this->redirectTo('/');
    }
    public function doUpload(){
       
        Common::debug($_FILES); 
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
                        if(move_uploaded_file($Filedata['tmp_name'], $tmp_file)){
                                $img = new Imagick($tmp_file);
                                $width = $img->getImageWidth();
                                $dest_file = './images/tmp/'.$key.'.'.$ext;
                                if($width>300){
                                    $img->thumbnailImage(300, 0); 
                                }
                                $width  = $img->getImageWidth();
                                $height = $img->getImageHeight();
                                if($height>400){
                                    $height=400;
                                }
                                $img->cropImage($width, $height, 0,0);
                                $width  = $img->getImageWidth();
                                $height = $img->getImageHeight();
                                $img->writeImage($dest_file);    
                                $dest_file = ltrim($dest_file, '.'); 
                                echo json_encode(array('img'=>$dest_file, 'height'=>$height, 'width'=>$width)); 
                        }
                }
        }  
    }    
   
    public function updateAvartar(){
        $x1 = $_POST['x1'];
        $y1 = $_POST['y1'];
        $x2 = $_POST['x2'];
        $y2 = $_POST['y2'];    
        $url = $_POST['img'];
        $uid = $_POST['uid'];
        $url = ".".$url; 
        $img = new Imagick($url);
        $img->cropImage(180, 180, $x1, $y1);
        $key = md5(Ip::getClientIp().uniqid().$url.time());
        $dest = './images/tmp/'."avartar".$key.".jpg";
        $img->writeImage($dest);   
        $updateArr = array();
        $whereArr = array();
        $updateArr['avartar'] = ltrim($dest, '.');
        $whereArr['id'] = $uid;
        $userinfoDB = new UserinfoModelDB();
        $userinfoDB->update($updateArr, $whereArr);
        echo json_encode(array('img'=>ltrim($dest, '.')));
    } 
   
    public function doRegist(){
        if(!empty($_POST))
        { 
            if(!isset($_GET['ref'])||$_GET['ref']==''){
                $ref = "/?s=userinfo&a=regist"; 
            }else{
                $ref = $_GET['ref'];
            }
            $email = trim($_POST['email']);
            $password = trim($_POST['password']); 
            $username = trim($_POST['username']); 
            $desc = trim($_POST['desc']);
            $userinfoDB = new UserinfoModelDB();
            $insertValue = array();
            $insertValue['username']  = $username; 
            $insertValue['sex']  = 'F';
            $insertValue['email'] = $email; 
            $insertValue['password']  = md5($password);
            $insertValue['desc'] = $desc;
            if($_POST['sex']=='M'){
                $insertValue['avartar']  = "/images/male_180.png";
            }else{
                $insertValue['avartar']  = "/images/female_180.png";
            }
            $userinfoDB->insert($insertValue); 
            Session::set('email', $email);
            Session::set('password',$password); 
            $userinfo = $userinfoDB->getUserinfoByEmail($email);
            $mail = new Mailer();
            $body = "请点击链接以完成账号激活:http://mayhope.com/userinfo/active/".$userinfo['id'];
            $mail->mySend("青红图片分享注册激活", $body , $email); 
            $this->redirectTo($ref);
        }else{
            $this->display('regist.html');
        }
    }

    public function active(){
        $id = $_GET['id'];
        $userinfoDB = new UserinfoModelDB();
        $update = array();
        $where  = array();
        $update['flag'] = 1;
        $where['id'] = $id;
        $userinfoDB->update($update, $where); 
        $userinfo = $userinfoDB->getUserinfoById($id);
        Session::set('email', $userinfo['email']);
        $this->redirectTo('/'); 
    }

    public function doLogin(){                    
        $reffer =  $_POST['reffer']; 
        if(!empty($_POST)){
            Session::set('email', $_POST['email']);
            Session::set('password', md5($_POST['password'])); 
        }else{
            $reffer="/?s=userinfo&a=login";
        }
        header("Location:$reffer"); 
    } 
    public function checkEmail(){
        $email = $_POST['email'];
        Common::debug($email);
        $userinfoDB = new UserinfoModelDB();
        $sqlEmail = "SELECT * FROM ".$userinfoDB->getTableName()." WHERE email="."'".$email."'";
        $rs = $userinfoDB->getData($sqlEmail);
        if(empty($rs)){
            echo json_encode(array('Y'));
        }else{
            echo json_encode(array('N'));
        }
    }
    public function loginCheck(){
        if(!empty($_POST)){
            $userinfoDB = new UserinfoModelDB();
            $sqlEmail = "SElECT * FROM ".$userinfoDB->getTableName()." WHERE `email`=?";
            $emailInfo = $userinfoDB->getData($sqlEmail,array($_POST['email']));
            if(empty($emailInfo)){
                echo json_encode("M");
            }else{
                $sql = "SELECT * FROM ".$userinfoDB->getTableName()." WHERE `email`=? AND `password`=?";
                $rs = $userinfoDB->getData($sql,array($_POST['email'],md5($_POST['password'])));
                if(empty($rs)){
                    echo json_encode("N");
                }else{  
                    echo json_encode("Y");
                }
            } 
        }
    }
    
    public function view(){
        $userinfoDB = new UserinfoModelDB();
        $newUsers = $userinfoDB->getNewUsers();
        $activeUsers = $userinfoDB->getActiveUsers();
        $this->setView('activeUsers', $activeUsers);
        $this->setView('newUsers', $newUsers);
        $this->display('userlist.html');
    }
}
