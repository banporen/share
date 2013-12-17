<?php
/*
 * Copyright (c) 2010,  新浪网运营部-网络应用开发部
 * All rights reserved.
 * @description: 相册表DB类
 * @author: **
 * @date: 2010/07/15
 * @version: 1.0
 */
class AlbumModelDB extends MyDB {
    
    //field_arr start
    protected $field_arr = array (
      'id' => 
      array (
        'name' => 'ID',
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
      'name' => 
      array (
        'name' => '名字',
        'type' => 'varchar',
        'max_length' => '50',
        'validate' => 0,
      ),
      'desc' => 
      array (
        'name' => '描述',
        'type' => 'varchar',
        'max_length' => '500',
        'validate' => 0,
      ),
      'scan_count' => 
      array (
        'name' => '浏览',
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
        //field_arr end 49c60a69acbdf0d19814fff0f041d0d81
    
    public function __construct($dbname = null, array $db_config = array()) {
        parent::__construct($dbname, $db_config);
        parent::setTableName("album");
    }
}
?>