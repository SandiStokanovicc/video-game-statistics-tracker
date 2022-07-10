<?php
require_once __DIR__.'/BaseDao.class.php';

class RecentSearchesDao extends BaseDao {

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("recentSearches");
  }

  public function getSummonerNameRegion($summonerName, $region){
    return $this->query_unique("SELECT * FROM recentSearches WHERE summonerName = :summonerName AND region = :region",
    ['summonerName' => $summonerName, 'region' => $region]);
  }
}
?>