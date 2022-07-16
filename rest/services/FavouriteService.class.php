<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/FavouriteDao.class.php';

class FavouriteService extends BaseService {

  public function __construct() {
    parent::__construct(new FavouriteDao());
  }

  public function getFavouriteById($userId){
    return $this->dao->getFavouriteById($userId);
  }

  public function getIdAndSummonerName($userId, $summonerName){
    return $this->dao->getIdAndSummonerName($userId, $summonerName);
  }
}
