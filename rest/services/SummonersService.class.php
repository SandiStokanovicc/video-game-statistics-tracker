<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/SummonersDao.class.php';

class SummonersService extends BaseService {

  public function __construct() {
    parent::__construct(new SummonersDao());
  }


}
