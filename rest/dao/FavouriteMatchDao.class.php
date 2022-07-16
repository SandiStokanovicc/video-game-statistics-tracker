<?php
require_once __DIR__.'/BaseDao.class.php';

class FavouriteMatchDao extends BaseDao {

  public function __construct(){
    parent::__construct("favmatches");
  }

  public function getFavouriteMatchesByUserId($userId){
    return $this->query_specific("SELECT * FROM favmatches WHERE userId = $userId", ['userId' => $userId]);
  }

  public function getIdMatchIDContinent($userId, $APIMatchID, $continent){
    return $this->query_unique("SELECT * FROM favmatches WHERE userId = :userId AND APIMatchID = :APIMatchID AND continent = :continent", ['userId' => $userId, 'APIMatchID' => $APIMatchID, 'continent' => $continent]);
  }
  
  public function countFavMatchesByID($userId){
    return $this->query_unique("SELECT COUNT(*) AS count FROM favmatches WHERE userId = :userId", ['userId' => $userId]);
  }
  
}