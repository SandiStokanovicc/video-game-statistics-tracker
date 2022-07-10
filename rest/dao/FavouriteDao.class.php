<?php
require_once __DIR__.'/BaseDao.class.php';

class FavouriteDao extends BaseDao {

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("favourites");
  }

  public function getFavouriteById($userId){
    return $this->query_specific("SELECT * FROM favourites WHERE userId = $userId", ['userId' => $userId]);
  }

  public function getIdAndSummonerName($userId, $summonerName){
    return $this->query_unique("SELECT * FROM favourites WHERE userId = :userId and summonerName = :summonerName", ['userId' => $userId, 'summonerName' => $summonerName]);
  }
}