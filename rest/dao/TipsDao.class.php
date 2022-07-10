<?php
require_once __DIR__.'/BaseDao.class.php';

class TipsDao extends BaseDao {

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("tips");
  }
  public function getTip(){
    return $this->query_specific("SELECT * from tips ORDER BY RAND() LIMIT 1");
  }
}
