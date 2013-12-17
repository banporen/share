<?php
class MessageController extends ModelController{
		
	public function getMessageToMe(){
        $email = Session::get('email');
        if($email){
            $userinfo = $userinfoDB->getUserinfoByEmail($email);
            $messageList = $messageDB->getMessageToMe($userinfo['id']); 
            if($messageList){
                Message::showSucc('ok', $messageList);                
            }else{
                Message::showError('error');
            }
        }else{
            Message::showError('error');
        }
    } 
    public function get(){
        $id = $_POST['uid'];
        $messageDB = new MessageModelDB();
        $message = $messageDB->getMessageToMe($id);
        if($message){
            $url = $message['url'];
            if(preg_match('/\?/', $url)){
                $url .= "&message=".$message['id'];
            }else{
                $url .= "?message=".$message['id'];
            }
            $message['url'] = $url;
        } 
        if($message){
            Message::showSucc('ok', $message);                
        }else{
            Message::showError('error');
        } 
    }
    //消息队列
    //评论照片
    //回复评论
    //收藏照片
    //分享照片

      
}
