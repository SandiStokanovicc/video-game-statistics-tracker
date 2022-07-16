<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/FavouriteMatchDao.class.php';

class FavouriteMatchService extends BaseService {

  public function __construct() {
    parent::__construct(new FavouriteMatchDao());
  }

  public function getFavouriteMatchesByUserId($userId){
    return $this->dao->getFavouriteMatchesByUserId($userId);
  }

  public function getIdMatchIDContinent($userId, $APIMatchID, $continent){ 
    return $this->dao->getIdMatchIDContinent($userId, $APIMatchID, $continent);
  }

  public function countFavMatchesByID($userId){
    return $this->dao->countFavMatchesByID($userId);
  }
}
?>