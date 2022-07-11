<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/FavouriteMatchDao.class.php';

class FavouriteMatchService extends BaseService {

  public function __construct() {
    parent::__construct(new FavouriteMatchDao());
  }

  public function getFavouriteMatchesByUserId($userId){
    return $this->dao->getFavouriteMatchesByUserId($userId);
    //TREBAT CE OVDJE DODATI DOSTA STVARI, tj. pozivati API
    //moze mozda preko Flight::riotService() idk
  }

  public function getIdAndMatchID($userId, $summonerName){
    return $this->dao->getIdAndMatchID($userId, $summonerName);
  }
  
}
