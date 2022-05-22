<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/UserDao.class.php';

class UserService extends BaseService {

  public function __construct() {
    parent::__construct(new UserDao());
  }

  public function getUserByEmail($email){
    return $this->dao->getUserByEmail($email);
  }

}
