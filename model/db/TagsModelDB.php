<?php
/*
 * Copyright (c) 2010,  新浪网运营部-网络应用开发部
 * All rights reserved.
 * @description: 标签表DB类
 * @author: **
 * @date: 2010/07/15
 * @version: 1.0
 */
class TagsModelDB extends MyDB {
    
    //field_arr start
    protected $field_arr = array (
      'id' => 
      array (
        'name' => 'ID',
        'type' => 'int',
        'max_length' => '10',
        'validate' => 0,
      ),
      'name' => 
      array (
        'name' => '名称',
        'type' => 'varchar',
        'max_length' => '32',
        'validate' => 0,
      ),
      'date_time' => 
      array (
        'name' => '时间',
        'type' => NULL,
        'max_length' => NULL,
        'validate' => 0,
      ),
      'pid' => 
      array (
        'name' => '图片',
        'type' => 'int',
        'max_length' => '10',
        'validate' => 0,
      ),
      'count' => 
      array (
        'name' => '数量',
        'type' => 'int',
        'max_length' => '10',
        'validate' => 0,
      ),
    );
        //field_arr end 27aab5beddc5d07b515b20d7679001b31
    
    public function __construct($dbname = null, array $db_config = array()) {
        parent::__construct($dbname, $db_config);
        parent::setTableName("tags");
    }

    public function add($pid, $name){
        $insert['name'] = $name;
        $insert['pid'] = $pid;
        $insert['count'] = 1;
        $this->insert($insert);
    }
    
    public function up($pid, $name){
        $sql = "update tags set count=count+1 where pid=".$pid." and name='".$name."'";
        $this->exec($sql);
    }

    public function isExist($pid, $name){
        $sql = "select id from ".$this->getTableName()." where pid=? and name=?";
        $data = $this->getRow($sql, array($pid, $name));
        if($data)return true;
        else return false;
    }

    public function getTagsByPid($pid){
        $sql = "select * from ".$this->getTableName()." where pid=?";
        $rs = $this->getData($sql, array($pid));
        return $rs;
    }

    public function getHotTags(){
        $sql = "select * from ".$this->getTableName()." order by count desc limit 20";
        $rs = $this->getData($sql);
        return $rs;
    }
    
}
?>
