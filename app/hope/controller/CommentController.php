<?php
class CommentController extends ModelController{
		
	public function doComment(){
        
            $value = array();	       
            $commentDB = new CommentModelDB();
            $messageDB = new MessageModelDB();
            $value['to_uid'] = $_POST['to_uid']; 
	        $value['from_uid'] = $_POST['from_uid'];
            $value['pid'] = $_POST['pid'];
            $value['content'] = $_POST['text'];
            $value['date_time'] = date('Y-m-d H:i:s', time()); 
            $rs = $commentDB->insert($value); 
            $to      = '351936818@qq.com';
            $subject = 'The Subject';
            $message = "http://mayhope.com/home/view/".$_POST['from_uid'].":评论:http://mayhope.com/item/".$_POST['pid'];
            $headers = 'From: monitor@mayhope.com' . "\r\n" . 'Reply-To: webmaster@example.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
            
            if($rs){ 
                $val = array();
                $val['message'] = $_POST['text'];
                $val['from_uid'] = $_POST['from_uid'];
                $val['to_uid'] = $_POST['to_uid'];
                $val['url'] = 'http://mayhope.com/item/'.$_POST['pid'];
                $val['kind'] = 0;
                //$messageDB->insert($val);
                $data = array(
                    'time'=>'1秒钟之前',
                    'text'=>htmlspecialchars($value['content'], ENT_COMPAT),  
                );
                Message::showSucc('ok', $data);  
            
            }else{
                Message::showError('error');
            }
    } 
    public function reply(){
        $value = array();
        $userinfoDB = new UserinfoModelDB();
	    $commentDB = new CommentModelDB();
        $messageDB = new MessageModelDB();
        $userinfo = $userinfoDB->getUserinfoById($_POST['to_uid']);
	    $text = $_POST['content'];
	    $find = "回复@".$userinfo['username'].":";
        $text = str_replace($find,'' , $text);
	    $value['to_uid'] = $_POST['to_uid'];   
        $value['from_uid'] = $_POST['from_uid'];
        $value['pid'] = $_POST['pid'];
        $value['content'] = $text;
        $value['date_time'] = date('Y-m-d H:i:s', time());
        $value['flag'] = 1;
	    $rs = $commentDB->insert($value);  
        
            $to      = '351936818@qq.com';
            $subject = 'The Subject';
            $message = "http://mayhope.com/home/view/".$_POST['from_uid'].":评论:http://mayhope.com/picture/".$_POST['pid'];
            $headers = 'From: monitor@mayhope.com' . "\r\n" . 'Reply-To: webmaster@example.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
            

        if($rs){
            $val = array();
            $val['message'] = $text;
            $val['from_uid'] = $_POST['from_uid'];
            $val['to_uid'] = $_POST['to_uid'];
            $val['url'] = 'http://mayhope.com/picture/'.$_POST['pid'];
            $val['kind'] = 0;
            $messageDB->insert($val); 
            $data = array(
                'time'=>'1秒钟之前',
                'text'=>htmlspecialchars($text, ENT_COMPAT),  
            	'userinfo'=>$userinfo,
	        );
            Message::showSucc('ok', $data);  
        }else{
            Message::showError('error');
        }
    }    

    public function deletes(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $commentDB = new CommentModelDB();
        $rs = $commentDB->delete($where);
        if($rs) Message::showSucc('ok');
        else Message::showError('error');
    }     

    public function view(){
        $commentDB = new CommentModelDB();
        $userinfoDB = new UserinfoModelDB();
        $commentList = $commentDB->getCommentList();
        //print_r($commentList);exit;
        foreach($commentList as $key=>$value){
            $from_userinfo = $userinfoDB->getUserinfoById($value['from_uid']);
            $to_userinfo = $userinfoDB->getUserinfoById($value['to_uid']);
            $commentList[$key]['from_userinfo'] = $from_userinfo;
            $commentList[$key]['to_userinfo'] = $to_userinfo;    
            $commentList[$key]['timePass'] = $this->timePass($value['date_time']); 
        } 
        $this->setView('commentList', $commentList); 
        $this->display('message.html');
    }
}
