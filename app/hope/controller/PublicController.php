<?php
class PublicController extends ModelController{

	public function test(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		echo $user_agent;	
	}
	public function view(){	                
         
         $pictureDB = new PictureModelDB();
	     $userinfoDB = new UserinfoModelDB(); 
	     $pictureList = $pictureDB->getPictureList();
	     $commentDB = new CommentModelDB();
	     $collectDB  = new CollectModelDB();
	     foreach($pictureList as $key=>$value){
	            $pictureList[$key]['userinfo'] = $userinfoDB->getUserinfobyId($value['uid']);
	            $pictureList[$key]['timePass'] = $this->timePass($value['date_time']);
	            $commentCount = $commentDB->getCommentCountByPid($value['id']);
	            $pictureList[$key]['comment_count'] = $commentCount;
	            if($this->islogin()){
	                $email = Session::get('email');
	                $userinfo = $userinfoDB->getUserinfoByEmail($email);
	                $flag = $collectDB->isCollected($value['id'], $userinfo['id']); 
	                if($flag||($value['uid']==$userinfo['id'])){
	                    $pictureList[$key]['heart'] = true;
	                }else{
	                    $pictureList[$key]['heart'] = false;
	                }
                }
                $order_by_scan[$key]['scan_count'] = $value['scan_count'];
                $order_by_comment[$key]['comment_count'] = $value['comment_count'];
	    }
         
	    $pageStr   = $pictureDB->getPageStr();
	    $pageJump  = $pictureDB->getPageJump();     
        if(isset($_GET['t'])){
             if($_GET['t']=='follow'){
                   array_multisort($order_by_scan, SORT_DESC, $pictureList); 
             }else if($_GET['t']=='hot'){
                   array_multisort($order_by_comment, SORT_DESC, $pictureList);
             }
         }
		if(!isset($_GET['page'])){
				$page =1;
		}else{
				$page = $_GET['page'];
        }
        //微博授权地址
        $o = new SaeTOAuthV2Model( WB_AKEY , WB_SKEY );
        $code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
        $this->setView('code_url', $code_url );
        
        if(isset($_SESSION['token'])){
              setcookie( 'weibojs_'.$o->client_id, http_build_query($_SESSION['token']) );
              $c = new SaeTClientV2Model( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
              $ss = $o->getTokenExpiredSecond($_SESSION['token']['access_token']);
             /** 
              echo "<pre>";
              print_r($ss);
              exit;
              */
               
              if(is_object($c)){
                  $uid  = $c->get_uid(); // done
                  $wb_user = $c->show_user_by_id($uid['uid']);
                  $this->setView('wb_user', $wb_user);     
              }else{
                  //登陆授权页面 
                  header("Location:$code_url");     
                  $this->setView('wb_user', '');
              }
        }
		$this->setView('page', $page);
	    $this->setView('tab', 'index'); 
	    $this->setView('pageStr', $pageStr);
	    $this->setView('pageJump', $pageJump);         
	    $this->setView('pictureList', $pictureList);  
		if(isset($_GET['m'])||$this->isMobile()){
			$this->display('wap/index.html');			
		}else{
				$this->display('index.html');
		} 
    } 

    public function trend(){
        $id = $_POST['id'];
        $pictureDB = new PictureModelDB();
        $userinfoDB = new UserinfoModelDB();
        $pictureInfo = $pictureDB->getPictureById($id);
        $userinfo = $userinfoDB->getUserinfoById($pictureInfo['uid']);
        $pictureInfo['userinfo'] = $userinfo;
        if($pictureInfo){
            Message::showSucc('ok', $pictureInfo);
        }else{
            Message::showError('error');
        }
    }

    public function search(){
        $pictureDB = new PictureModelDB();
        $tagsDB = new TagsModelDB();
        $TplinkDB = new TplinkModelDB();
        $key = $_POST['key'];
        $tagsInfo = $tagsDB->getTagsByName($key); 
    }

    public function wb(){
        $o = new SaeTOAuthV2Model( WB_AKEY , WB_SKEY );
        $code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
        echo '<a href="'.$code_url.'">用微博登录</a>';
    }
    

    public  function callback(){
        session_start();
        $o = new SaeTOAuthV2Model( WB_AKEY , WB_SKEY );

        if (isset($_REQUEST['code'])) {
                $keys = array();
                $keys['code'] = $_REQUEST['code'];
                $keys['redirect_uri'] = WB_CALLBACK_URL;
                try {
                     $token = $o->getAccessToken( 'code', $keys ) ;
                } catch (BaseModelOAuthException $e) {}
        }

        if ($token) {
                $_SESSION['token'] = $token;
                setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
                header("Location:http://oicu.me"); 
        }else{
            echo 'error';
        }
    }    
}
