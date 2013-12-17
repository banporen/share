<?php
/*
 * Copyright (c) 2010,  新浪网运营部-网络应用开发部
 * All rights reserved.
 * @description: 消息表DB类
 * @author: **
 * @date: 2010/07/15
 * @version: 1.0
 */
class MessageModelDB extends MyDB {
    
    //field_arr start
    protected $field_arr = array (
      'id' => 
      array (
        'name' => 'ID',
        'type' => 'bigint',
        'max_length' => '10',
        'validate' => 0,
      ),
      'from_uid' => 
      array (
        'name' => '来自',
        'type' => 'int',
        'max_length' => '10',
        'validate' => 0,
      ),
      'to_uid' => 
      array (
        'name' => '发给',
        'type' => 'int',
        'max_length' => '10',
        'validate' => 0,
      ),
      'kind' => 
      array (
        'name' => '类型',
        'type' => 'tinyint',
        'max_length' => '1',
        'validate' => 0,
      ),
      'message' => 
      array (
        'name' => '消息',
        'type' => 'varchar',
        'max_length' => '255',
        'validate' => 0,
      ),
      'url' => 
      array (
        'name' => '链接',
        'type' => 'varchar',
        'max_length' => '200',
        'validate' => 0,
      ),
      'flag' => 
      array (
        'name' => '查看',
        'type' => 'int',
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
        //field_arr end 45f904cb0fb18d080d6371f7e4dc628a1
    
    public function __construct($dbname = null, array $db_config = array()) {
        parent::__construct($dbname, $db_config);
        parent::setTableName("message");
    }

public function getMessageToMe($uid){
            $sql = "select * from ".$this->getTableName()." where to_uid=? and flag=0 order by id desc limit 1";
            $rs = $this->getRow($sql, array($uid)); 
            if($rs)
                return $rs;
            else    
                return false; 
    } 
    public function getMessageCount($uid){
            $sql = "select count(*) from ".$this->getTableName()." where to_uid=? and flag=0 ";
            $rs = $this->getRow($sql, array($uid));
            if($rs)
                return $rs['count(*)'];
            else    
                return false; 
    }

}
?>
