<?php
/**
* 路由配置信息
* 具体配置请参考http://wiki.intra.sina.com.cn/pages/viewpage.action?pageId=5509598
*/
class RouterConfig {
    static public $baseUrl = array(
        DAGGER_APP_HOPE=>'',
        DAGGER_APP_ADMIN=>''
    );

    //注：如果没有设置以上的默认路由，配置数组的最后一个为默认路由，请勿轻易修改
    static public $config = array(
        DAGGER_APP_HOPE=>array(
                'upload'=>array(
                        'upload'=>'',
                        'view'=>'', 
                ),
                'item'=>array(
                        'view'=>'<id?\d+>',
                ),
                'userinfo'=>array(
                        'password'=>'',
                        'infoset'=>'',
                        'login'=>'',
                        'regist'=>'',
                        'active'=>'<id?\d+>',
                        'avartar'=>'',
                        'view'=>'',    
                ),
                'home'=>array(
                        'view'=>'<uid?\d+>',
                ),
                'dynamic'=>array(
                        'detail'=>'<id?\d+>',
                        'view'=>'',
                ),
                'comment'=>array(
                        'view'=>'',
                ), 
                'public'=>array(
                        'view'=>'',
                )
        ),
        DAGGER_APP_ADMIN => array(
            'admin'=>array(
                'login'=>''
            )
        ),
    );

    //默认controller或默认action
    static public $defaultRouter = array(
        DAGGER_APP_HOPE => array(
            'default_controller' => 'public',
            'default_action' => array(
                'public' => 'view',        //DefaultController的默认action为view
                'item'   => 'view',  //WeiboMonitorcontroller的默认action为show
                'upload' => 'view',
                'userinfo' => 'view',
                'home'     => 'view',
                'dynamic'  => 'view',
                'comment'  => 'view',
            )
        )
    );


    private function __construct() {
        return;
    }
}
