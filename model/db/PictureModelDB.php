<?php
/*
 * Copyright (c) 2010,  新浪网运营部-网络应用开发部
 * All rights reserved.
 * @description: 图片表DB类
 * @author: **
 * @date: 2010/07/15
 * @version: 1.0
 */
class PictureModelDB extends MyDB {
    
    //field_arr start
    protected $field_arr = array (
      'id' => 
      array (
        'name' => 'ID',
        'type' => 'bigint',
        'max_length' => '10',
        'validate' => 0,
      ),
      'album_id' => 
      array (
        'name' => '相册',
        'type' => 'int',
        'max_length' => '10',
        'validate' => 0,
      ),
	  'uid' => 
      array (
        'name' => 'UID',
        'type' => 'int',
        'max_length' => '10',
        'validate' => 0,
      ),
      'title' => 
      array (
        'name' => '标题',
        'type' => 'varchar',
        'max_length' => '20',
        'validate' => 0,
      ),
      'desc' => 
      array (
        'name' => '描述',
        'type' => 'varchar',
        'max_length' => '500',
        'validate' => 0,
      ),
      'face_url' => 
      array (
        'name' => '小图',
        'type' => 'varchar',
        'max_length' => '255',
        'validate' => 0,
      ),
      'original_url' => 
      array (
        'name' => '大图',
        'type' => 'varchar',
        'max_length' => '255',
        'validate' => 0,
      ),
      'good_count' => 
      array (
        'name' => '称赞',
        'type' => 'bigint',
        'max_length' => '10',
        'validate' => 0,
      ),
      'scan_count' => 
      array (
        'name' => '浏览',
        'type' => 'bigint',
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
        //field_arr end 44e87afdd81dc5e0067c9bfab759973a1
    
    public function __construct($dbname = null, array $db_config = array()) {
        parent::__construct($dbname, $db_config);
        parent::setTableName("picture");
    }

    public function getPictureList(){
            $sql = "select * from ".$this->getTableName()." order by date_time desc";
            $rs = $this->getData($sql, '',  20);
            
            return $rs;
    }   
   
    public function getPictureListByUid($uid){
        $sql = "select * from ".$this->getTableName()." where uid=? order by date_time desc";
        $rs = $this->getData($sql, array($uid));
        return $rs;
    }
 
     public function getPictureById($id){
        $sql = "select * from ".$this->getTableName()." where id = ? ";
        $rs = $this->getRow($sql, array($id));
        return $rs;
    }	

    public function getLikePictureList(){
        $sql = "select * from ".$this->getTableName()." order by scan_count desc limit 10";
        $rs = $this->getData($sql);
        return $rs;
    }

    public function getPictureRand(){
        $sql = "select * from ".$this->getTableName()." order by rand() limit 10";
        $rs = $this->getData($sql);
        return $rs;
    } 
   
    public function getPictureRandOne(){
        $sql = "select * from ".$this->getTableName()." order by rand() limit 1";
        $rs = $this->getRow($sql, '');
        return $rs['id'];
    }    
 
    public function updateScan($id){
        $sql = "update ".$this->getTableName()." set scan_count=scan_count+1 where id=".$id;
        $this->exec($sql);
    }
    
    public function updateGood($id){
        $sql = "update ".$this->getTableName()." set good_count=good_count+1 where id=".$id;
        $rs = $this->exec($sql);
        return $rs;
    }
	public function getHotList(){
        $sql = "select * from ".$this->getTableName()." order by scan_count desc limit 10";
        $rs = $this->getData($sql);
        return $rs;
    }

	public function getNewList(){
		$sql = "select * from ".$this->getTableName()." order by date_time desc limit 10";
        $rs = $this->getData($sql);
        return $rs;
	}
}
?>
