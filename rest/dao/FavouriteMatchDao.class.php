<?php
require_once __DIR__.'/BaseDao.class.php';

class FavouriteMatchDao extends BaseDao {

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("favMatches");
  }

  public function getFavouriteMatchesByUserId($userId){                        //preko ovoga mozes povlacit, samo ces ime tabele promijeniti
    return $this->query_specific("SELECT * FROM favMatches WHERE userId = $userId", ['userId' => $userId]);
  }

  public function getIdMatchIDContinent($userId, $APIMatchID, $continent){            //ovo se koristi za provjeravanje duplikata, pogledaj kako se u ruti addFavourite koristi, samo ces umjesto summonerName stavit matchID
    return $this->query_unique("SELECT * FROM favMatches WHERE userId = :userId AND APIMatchID = :APIMatchID AND continent = :continent", ['userId' => $userId, 'APIMatchID' => $APIMatchID, 'continent' => $continent]);
  }

  public function deleteFavouriteMatch($userId, $APIMatchID, $continent){
    return $this->query("DELETE FROM favMatches WHERE userId = :userId AND APIMatchID = :APIMatchID AND continent = :continent", ['userId' => $userId, 'APIMatchID' => $APIMatchID, 'continent' => $continent]);
  }
  
}