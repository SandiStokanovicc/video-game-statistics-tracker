<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/RecentSearchesDao.class.php';

class RecentSearchesService extends BaseService {

  public function __construct() {
    parent::__construct(new RecentSearchesDao());
  }

  public function getSummonerNameRegion($summonerName, $region){
    return $this->dao->getSummonerNameRegion($summonerName, $region);
  }
}
?>