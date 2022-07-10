<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/TipsDao.class.php';

class TipsService extends BaseService {

  public function __construct() {
    parent::__construct(new TipsDao());
  }

  public function getTip(){
    return $this->dao->getTip();
  }

}
