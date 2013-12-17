<?php
class HomeController extends ModelController{
		
	public function view(){	       
         $uid = $_GET['uid'];
         $page = isset($_GET['page']) ? $_GET['page'] : 1;
         $pageSize = 10;
         $params['page'] = $page; 
         $params[STATE] = 'home';
         $params[ACTION] = 'view';
         $params['uid'] = $uid;
         $pictureDB = new PictureModelDB();
         $userinfoDB = new UserinfoModelDB();
         $collectDB = new CollectModelDB();
         $commentDB = new CommentModelDB();
         $collectPictureList = $collectDB->getPictureListByUid($uid); 
         $ownPictureList = $pictureDB->getPictureListByUid($uid);
         $userinfo = $userinfoDB->getUserinfobyId($uid);
         $pictures = array();
         $total = count($collectPictureList) + count( $ownPictureList);
         $pm = new Page($total, $pageSize, $params); 
         foreach($collectPictureList as $key=>$value){
                $item = $pictureDB->getPictureById($value['pid']);
                $item['userinfo'] = $userinfo;  
                $item['timePass'] = $this->timePass($value['date_time']);
                $commentCount = $commentDB->getCommentCountByPid($value['pid']);
                $item['comment_count'] = $commentCount;
                $pictures[] = $item;
         }
	     foreach($ownPictureList as $key=>$value){
                $item = $value; 
                $item['userinfo'] = $userinfo;  
                $item['timePass'] = $this->timePass($value['date_time']);
                $commentCount = $commentDB->getCommentCountByPid($value['id']);
                $item['comment_count'] = $commentCount;
                $pictures[] = $item;
         }
        foreach($pictures as $key=>$value){
                $order_by_scan[$key]['scan_count'] = $value['scan_count'];
                $order_by_comment[$key]['comment_count'] = $value['comment_count'];
        }
        if(isset($_GET['t'])){
             if($_GET['t']=='follow'){
                   array_multisort($order_by_scan, SORT_DESC, $pictures); 
             }else if($_GET['t']=='hot'){
                   array_multisort($order_by_comment, SORT_DESC, $pictures);
             }
         } 
        $pictures = array_slice($pictures, ($page-1)*$pageSize, $pageSize); 
        if($this->islogin()){
                $email = Session::get('email');
                $login_userinfo = $userinfoDB->getUserinfoByEmail($email);
                if($login_userinfo['id']==$uid){
                    $this->setView('myself', true);
                }else{
                    $this->setView('myself', false);
                }
        }else{
                $this->setView('myself', false);
        }
        foreach($pictures as $key=>$value)
        { 
             if($this->islogin()){      
                      //当前登录用户的主页,或者被当前登录用户收藏了
                      $flag = $collectDB->isCollected($value['id'], $login_userinfo['id']);
                      if(($uid==$login_userinfo['id'])||$flag){
                            $pictures[$key]['heart'] = true; 
                      }else{
                            $pictures[$key]['heart'] = false;
                      }
              }
        }
        
        //取出我传的照片
        $pageStr   = $pm->getPageStr();
        $this->setView('tab', 'home');
        $this->setView('home_user', $userinfo);
        $this->setView('pageStr', $pageStr);
        $this->setView('pageJump', $pageJump);         
        $this->setView('pictureList', $pictures);  
        $this->display('home.html'); 
	}  
}
