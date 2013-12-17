<?php
class ItemController extends ModelController{
		
	public function view(){	       
            
            $id = $_GET['id']; 
            $messageDB = new MessageModelDB();
            if(isset($_GET['message'])){
                $mid  = $_GET['message'];
                $update['flag'] = 1;
                $where['id'] = $mid;
                $messageDB->update($update, $where);
             }
            $pictureDB = new PictureModelDB();
            $userinfoDB = new UserinfoModelDB();
            $commentDB = new CommentModelDB();
            $collectDB = new CollectModelDB(); 
            $tagsDB = new TagsModelDB();
            BaseModelSwitch::close('masterDbPostOnly');
            $pictureDB->updateScan($id);
            $picture = $pictureDB->getPictureById($id);
            $userinfo = $userinfoDB->getUserinfobyId($picture['uid']); 
            $picture['userinfo'] = $userinfo;
            $picture['timePass'] = $this->timePass($picture['date_time']);  
            if($this->islogin()){
                $email = Session::get('email');
                $userinfo = $userinfoDB->getUserinfoByEmail($email);
                $flag = $collectDB->isCollected($id, $userinfo['id']);
                Common::debug($flag);
                if($flag||($picture['uid']==$userinfo['id'])){
                    $picture['heart'] = true;
                }else{
                    $picture['heart'] = false;
                }
            }
            $commentList = $commentDB->getCommentListByPid($id); 
            foreach($commentList as $key=>$value){
                $userinfo = $userinfoDB->getUserinfoById($value['from_uid']);
                $commentList[$key]['userinfo'] = $userinfo; 
		        $to_userinfo = $userinfoDB->getUserinfoById($value['to_uid']);
                $commentList[$key]['to_userinfo'] = $to_userinfo;
		        $commentList[$key]['timePass'] = $this->timePass($value['date_time']);		
	        } 
            $collectCount = $collectDB->getCountByPid($id);
            $collecter = $collectDB->getUidsByPid($id); 
            if($collecter){
                foreach($collecter as $key=>$value){
                    $collecter[$key]['userinfo'] = $userinfoDB->getUserinfoById($value['uid']);
                }
                $this->setView('collecter', $collecter);
            }
            if(isset($_SESSION['token'])){
                    setcookie( 'weibojs_'.$o->client_id, http_build_query($_SESSION['token']) );
                     //header('Location:http://oicu.me');
                    $c = new SaeTClientV2Model( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
                    $uid  = $c->get_uid(); // done
                    $wb_user = $c->show_user_by_id($uid['uid']);
                    $this->setView('wb_user', $wb_user);
            }
            $tags = $tagsDB->getTagsByPid($picture['id']);
            $this->setView('tags', $tags);
            $this->setView('collectCount', $collectCount);
            $this->setView('tab', 'picture'); 
            $this->setView('commentList', $commentList); 
            $this->setView('picture', $picture);
            $this->display('picture.html'); 
	} 
    
    public function trend(){
            $pid = $_POST['pid'];
            $pictureDB = new PictureModelDB();
            $userinfoDB = new UserinfoModelDB();
            if($pid){
                    $picture = $pictureDB->getPictureById($pid);                
                    $picture['original_url'] = ltrim($picture['original_url'], '.');
                    $picture['face_url'] = ltrim($picture['face_url'], '.');
                    $userinfo = $userinfoDB->getUserinfoById($picture['uid']);
                    $picture['userinfo'] = $userinfo;
                    echo json_encode($picture);
            }
    }     
    
        
    
    public function getPictureRand(){
           $pictureDB = new PictureModelDB();
           $data = $pictureDB->getPictureRand(); 
           if($data){
                Message::showSucc('ok', $data);
           }else{
                Message::showError('error');
            }
    }    

    public function collect(){
        $pid = $_POST['pid'];
        $uid = $_POST['uid'];
        $collectDB = new CollectModelDB();
        //先判断是否已经收藏过了
        $flag = $collectDB->isCollected($pid, $uid);
        if($flag){
            Message::showError('error'); 
        }else{
            $value = array();
            $value['pid'] = $pid;
            $value['uid'] = $uid;
            $rs = $collectDB->insert($value);
            if($rs){
                Message::showSucc('ok');
            }else{
                Message::showError('error');
            }
        }
    }   

    public function good(){
        $pid = $_POST['pid'];
        $pictureDB = new PictureModelDB();
        $rs = $pictureDB->updateGood($pid);
        if($rs){
            Message::showSucc('ok', $rs);
        }else{
            Message::showError('error');
        }
    }

    public function upDesc(){
		$text = $_POST['text'];
		$pid = $_POST['pid'];
   		$where['id'] = $pid; 
		$update['desc'] = $text;   
		$pictureDB = new PictureModelDB();
		$rs = $pictureDB->update($update, $where);
        if($rs){
		    $data = array(
			    'text' => htmlspecialchars($_POST['text'], ENT_COMPAT),
		    );	
		    Message::showSucc('ok', $data);
		}else{
			Message::showError('error');
		}	
	}

}
