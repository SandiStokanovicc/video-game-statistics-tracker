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

  public function deleteFavouriteMatch($user, $APIMatchID, $continent){
    $match = $this->dao->getIdMatchIDContinent($user['iduser'], $APIMatchID, $continent);
    if ($match['userId'] != $user['iduser']){
      throw new Exception("Impossible to delete someone else's match");
    }
    return $this->dao->deleteFavouriteMatch($user['iduser'], $APIMatchID, $continent);
  }

  public function countFavMatchesByID($userId){
    return $this->dao->countFavMatchesByID($userId);
  }
}
?>