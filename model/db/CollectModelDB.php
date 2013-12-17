<?php
/*
 * Copyright (c) 2010,  新浪网运营部-网络应用开发部
 * All rights reserved.
 * @description: 收藏表DB类
 * @author: **
 * @date: 2010/07/15
 * @version: 1.0
 */
class CollectModelDB extends MyDB {
    
    //field_arr start
    protected $field_arr = array (
      'id' => 
      array (
        'name' => 'ID',
        'type' => 'bigint',
        'max_length' => '10',
        'validate' => 0,
      ),
      'pid' => 
      array (
        'name' => '图片',
        'type' => 'bigint',
        'max_length' => '10',
        'validate' => 0,
      ),
      'uid' => 
      array (
        'name' => '用户',
        'type' => 'int',
        'max_length' => '10',
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
        //field_arr end 5ac0c5958b658d3248dff9f3bf08b9e41
    
    public function __construct($dbname = null, array $db_config = array()) {
        parent::__construct($dbname, $db_config);
        parent::setTableName("collect");
    }
    public function isCollected($pid, $uid){
        $sql = "select * from ".$this->getTableName()." where pid=? and uid=?";
        $rs = $this->getRow($sql, array($pid, $uid));
        if($rs){
            return true;
        }else{ 
            return false;
        }
    } 
    
    public function getUidsByPid($pid){
        $sql = "select * from ".$this->getTableName()." where pid=?";
        $rs = $this->getData($sql, array($pid));
        return $rs;
    }

    public function getCountByPid($pid){
        $sql = "select count(*) as num from ".$this->getTableName()." where pid=?";
        $rs = $this->getRow($sql, array($pid));
        return $rs['num'];
    }

    public function getPictureListByUid($uid){
        $sql  = "select * from ".$this->getTableName()." where uid=? order by id desc";
        $rs = $this->getData($sql, array($uid));
        return $rs;
    }

}
?>
