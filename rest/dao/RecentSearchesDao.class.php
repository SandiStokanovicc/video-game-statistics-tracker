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

  public function update($puuid, $entity, $id_column = "puuid"){
    $query = "UPDATE ".$this->table_name." SET ";
    foreach($entity as $name => $value){
      $query .= $name ."= :". $name. ", ";
    }
    $query = substr($query, 0, -2);
    $query .= " WHERE ${id_column} = :puuid";

    $stmt= $this->conn->prepare($query);
    $entity['puuid'] = $puuid;
    $stmt->execute($entity);
  }
}
?>