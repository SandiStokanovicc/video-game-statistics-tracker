<?php

/**
* @OA\Get(path="/summoners/{summonerName}/{region}", tags={"riot"},
*     @OA\Parameter(in="path", name="summonerName", example="Condemn for Stun", description="player's name"),
*     @OA\Parameter(in="path", name="region", example="eun1", description="player's server / region"),
*     @OA\Response(response="200", description="Fetch last 5 matches for player")
* )
*/

Flight::route('GET /summoners/@summonerName/@region', function($summonerName, $region){ 
  $presentInDB = Flight::recentSearchesService()->getSummonerNameRegion($summonerName, $region);
  if(!empty($presentInDB)){
    Flight::json(Flight::riotService()->getRecentSummonerMatches($presentInDB));
  }
  else{
    $responseJSON = Flight::riotService()->getSummonerMatches($summonerName, $region);
    $dbEntity = array();
    $dbEntity['profileIconId'] = $responseJSON['profileIconId'];
    $dbEntity['summonerLevel'] = $responseJSON['summonerLevel']; 
    $dbEntity['summonerName'] = $summonerName;
    $dbEntity['region'] = $region;
    $dbEntity['puuid'] = $responseJSON['puuid'];
    $dbEntity['encryptedSummonerId'] = $responseJSON['id'];
    Flight::recentSearchesService()->add($dbEntity);
    Flight::json($responseJSON);
  }
});

Flight::route("GET /summonersMobileAPI/@summonerName/@region",  function($summonerName, $region){
   Flight::json(Flight::riotService()->getSummonerMatchesMobileAPI($summonerName, $region));
});
?>