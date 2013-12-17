<?php
/*
 * Copyright (c) 2010,  新浪网运营部-网络应用开发部
 * All rights reserved.
 * @description: 评论表DB类
 * @author: **
 * @date: 2010/07/15
 * @version: 1.0
 */
class CommentModelDB extends MyDB {
    
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
      'to_uid' => 
      array (
        'name' => '评论对象',
        'type' => 'int',
        'max_length' => '10',
        'validate' => 0,
      ),
      'from_uid' => 
      array (
        'name' => '评论来源',
        'type' => 'int',
        'max_length' => '10',
        'validate' => 0,
      ),
      'content' => 
      array (
        'name' => '内容',
        'type' => 'varchar',
        'max_length' => '500',
        'validate' => 0,
      ),
      'date_time' => 
      array (
        'name' => '时间',
        'type' => NULL,
        'max_length' => NULL,
        'validate' => 0,
      ),
      'flag' => 
      array (
        'name' => '审核',
        'type' => 'tinyint',
        'max_length' => '1',
        'validate' => 0,
      ),
    );
        //field_arr end b42d8f489e51bfe49fb6de25a8176dae1
    
    public function __construct($dbname = null, array $db_config = array()) {
        parent::__construct($dbname, $db_config);
        parent::setTableName("comment");
    }

public function getCommentListByPid($id){
        $sql = "select * from ".$this->getTableName()." where pid=? order by date_time desc";
        $rs = $this->getData($sql, array($id));
        return $rs;
    }
    
    public function getCommentCountByPid($id){
        $sql = "select count(*) from ".$this->getTableName()." where pid=?";
        $rs = $this->getRow($sql, array($id));
        return $rs['count(*)'];
    }

    public function getActiveUids(){
        $sql = "select count(*) as num, b.* from comment b  group by from_uid order by num desc";
        $data = $this->getData($sql);
        return $data;
    }
    
    public function getCommentList(){
        $sql = "select * from ".$this->getTableName()." order by id desc limit 20";
        $rs = $this->getData($sql);
        return $rs;
    }

}
?>
