<?php
/*
 * Copyright (c) 2010,  新浪网运营部-网络应用开发部
 * All rights reserved.
 * @description: 用户信息表DB类
 * @author: **
 * @date: 2010/07/15
 * @version: 1.0
 */
class UserinfoModelDB extends MyDB {
    
    //field_arr start
    protected $field_arr = array (
      'id' => 
      array (
        'name' => 'ID',
        'type' => 'int',
        'max_length' => '10',
        'validate' => 0,
      ),
      'email' => 
      array (
        'name' => '邮箱',
        'type' => 'varchar',
        'max_length' => '50',
        'validate' => 0,
      ),
      'password' => 
      array (
        'name' => '密码',
        'type' => 'varchar',
        'max_length' => '32',
        'validate' => 0,
      ),
      'username' => 
      array (
        'name' => '昵称',
        'type' => 'varchar',
        'max_length' => '20',
        'validate' => 0,
      ),
      'sex' => 
      array (
        'name' => '性别',
        'type' => 'char',
        'max_length' => '1',
        'validate' => 0,
      ),
      'avartar' => 
      array (
        'name' => '大头像',
        'type' => 'varchar',
        'max_length' => '255',
        'validate' => 0,
      ),
      'small_avartar' => 
      array (
        'name' => '小头像',
        'type' => 'varchar',
        'max_length' => '255',
        'validate' => 0,
      ),
      'link' => 
      array (
        'name' => '博客',
        'type' => 'varchar',
        'max_length' => '200',
        'validate' => 0,
      ),
      'desc' => 
      array (
        'name' => '签名',
        'type' => 'varchar',
        'max_length' => '200',
        'validate' => 0,
      ),
      'flag' => 
      array (
        'name' => '审核',
        'type' => 'tinyint',
        'max_length' => '1',
        'validate' => 0,
      ),
      'date_time' => 
      array (
        'name' => '时间',
        'type' => NULL,
        'max_length' => NULL,
        'validate' => 0,
      ),
    );
        //field_arr end ff60af111dffd333267bf0af6304facd1
    
    public function __construct($dbname = null, array $db_config = array()) {
        parent::__construct($dbname, $db_config);
        parent::setTableName("userinfo");
    }

     public function getUserinfoByEmail($email){ 
        if($email){            
                $sql = "select * from ".$this->getTableName()." where email=?";
                $rs  =  $this->getRow($sql, array($email));
                return $rs; 
        }else{
            return false;
        }
    }
    
    public function getUserinfoById($id){
        if($id){
            $sql = "select * from ".$this->getTableName()." where id=?";
            $rs  =  $this->getRow($sql, array($id));
            return $rs; 
        }else{
            return false;
        }
    }

    public function getUserinfoList($limit){
        $sql = "select * from ".$this->getTableName()."  order by date_time desc limit ".$limit;
        $rs = $this->getData($sql);
        return $rs;
    }
    
    public function getNewUsers(){
        $sql = "select * from ".$this->getTableName()."  order by date_time desc limit 30";
        $rs = $this->getData($sql);
        return $rs;
    }

    public function getActiveUsers(){
        $commentDB = new CommentModelDB();
        $uids = $commentDB->getActiveUids();
        $data = array();
        foreach($uids as $key=>$value){
            $rs = $this->getUserinfoById($value['from_uid']);
            $data[] = $rs; 
        }
        return $data;
    }
}
?>
