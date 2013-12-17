<?php
class ModelController extends BaseController{
   
    /*
     *
     *  登录状态信息
     */
     
    public function __construct($state, $action){
        parent::__construct($state, $action);
        
	    $email = Session::get('email');
        $userinfoDB = new UserinfoModelDB();
        if($email){ 
                $userinfo = $userinfoDB->getUserinfoByEmail($email); 
                $this->setView('userinfo', $userinfo);  
                $this->setView('login', 'Y');
        }else{
                $this->setView('login', 'N');
        }
        $pictureDB = new PictureModelDB();
        $likeList = $pictureDB->getPictureRand();
        foreach($likeList as $key=>$value){
                $userinfo = $userinfoDB->getUserinfoById($value['uid']);
                $likeList[$key]['userinfo'] = $userinfo;  
        }
        $userlist = $userinfoDB->getUserinfoList(16);
        $this->setView('userlist', $userlist);
        $pid = $pictureDB->getPictureRandOne();
        $this->setView('pid', $pid);
        $tagsDB = new TagsModelDB();
        $hotTags = $tagsDB->getHotTags();
        $hot   = $pictureDB->getHotList();
		$new   = $pictureDB->getNewList();
		$this->setView('new', $new);
		$this->setView('hot', $hot);
		$this->setView('hotTags', $hotTags); 
        $this->setView('likeList', $likeList);
    }    
    
    public function islogin(){
        $email = Session::get('email');
        if($email){
            return true;
        }else{
            return false;
        }
    }
    public function timePass($time){
       
        $time      = strtotime($time);
        $oneMinute = 60;
        $oneHour   = 3600;
        $oneDay    = 86400;
        $oneMonth  = 2592000;
        $oneYear   = 31104000;
        $tmp = '';
        $timeCha = time()-$time-8*3600;
        if($timeCha < $oneMinute){
            $second = $timeCha;
            $tmp = $second."秒前";
        }else if($timeCha >= $oneMinute && $timeCha < $oneHour){
            $minute = floor($timeCha/$oneMinute);
            $tmp    = $minute."分钟之前";
        }else if($timeCha >= $oneHour && $timeCha < $oneDay){
            $hour = floor($timeCha/$oneHour);
            $tmp  = $hour."小时之前";
        }else if($timeCha >= $oneDay && $timeCha < $oneMonth){
            $day  = floor($timeCha/$oneDay);
            $tmp  = $day."天之前";
        }else if($timeCha >= $oneMonth && $timeCha < $oneYear){
            $month = floor($timeCha/$oneMonth);
            $tmp   = $month."月之前";
        }else if($timeCha >=$oneYear){
            $year  = floor($timeCha/$oneYear);
            $tmp   = $year."年之前";
        }
        return $tmp; 
    } 

	public	function isMobile()
	{
	    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
	    {
	        return true;
	    }
	    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	    if (isset ($_SERVER['HTTP_VIA']))
	    {
	        // 找不到为flase,否则为true
	        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	    }
	    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
	    if (isset ($_SERVER['HTTP_USER_AGENT']))
	    {
	        $clientkeywords = array ('nokia',
	            'sony',
	            'ericsson',
	            'mot',
	            'samsung',
	            'htc',
	            'sgh',
	            'lg',
	            'sharp',
	            'sie-',
	            'philips',
	            'panasonic',
	            'alcatel',
	            'lenovo',
	            'iphone',
	            'ipod',
	            'blackberry',
	            'meizu',
	            'android',
	            'netfront',
	            'symbian',
	            'ucweb',
	            'windowsce',
	            'palm',
	            'operamini',
	            'operamobi',
	            'openwave',
	            'nexusone',
	            'cldc',
	            'midp',
	            'wap',
	            'mobile'
	        );
	        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
	        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
	        {
	            return true;
	        }
	    }
	    return false;
	}	
    
    public function loginStatus(){
        if(isset($_SESSION['token'])){
              setcookie( 'weibojs_'.$o->client_id, http_build_query($_SESSION['token']) );
              $c = new SaeTClientV2Model( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
              if(is_object($c)){
                  return true;     
              }else{
                  return false;
              }
        }else{
             return false;
        }

    }
}
?>
