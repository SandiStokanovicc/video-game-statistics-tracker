<?php

/**
* @OA\Get(path="/summoners/{summonerName}/{region}", tags={"riot"},
*     @OA\Parameter(in="path", name="summonerName", example="Condemn for Stun", description="player's name"),
*     @OA\Parameter(in="path", name="region", example="eun1", description="player's server / region"),
*     @OA\Response(response="200", description="Fetch last 5 matches for player"),
*     @OA\Response(response="403", description="Forbidden... Incorrect API key / API key level not high enough"),
*     @OA\Response(response="404", description="Error: Data not found - summoner not found"),
*     @OA\Response(response="503", description="Service unavailable... RIOT API key outdated or RIOT API server fail")
* )
*/

Flight::route('GET /summoners/@summonerName/@region', function($summonerName, $region){ 
  $presentInDB = Flight::recentSearchesService()->getSummonerNameRegion($summonerName, $region);
  if(!empty($presentInDB)){
    $difftime = strtotime(date('Y-m-d H:i:s')) - strtotime($presentInDB['timeUpdated']);
    $days = $difftime/24/60/60;
    var_dump($days);
    if($days < 1){
//      var_dump("IN DB; Using recentSummonerMatches"); die;
      Flight::json(Flight::riotService()->getRecentSummonerMatches($presentInDB));
    } 
    else{
//      var_dump("IN DB; UPDATING; Using getSummonerMatches"); die;
      $responseJSON = Flight::riotService()->getSummonerMatches($summonerName, $region);
      $dbEntity = array();
      $dbEntity['profileIconId'] = $responseJSON['profileIconId'];
      $dbEntity['summonerLevel'] = $responseJSON['summonerLevel']; 
      $dbEntity['summonerName'] = $summonerName;
      $dbEntity['region'] = $region;
      $dbEntity['puuid'] = $responseJSON['puuid'];
      $dbEntity['encryptedSummonerId'] = $responseJSON['id'];
      $dbEntity['timeUpdated'] = new DateTime(date('Y-m-d H:i:s')); 
      $dbEntity['timeUpdated'] = $dbEntity['timeUpdated']->format('Y-m-d H:i:s');
      Flight::recentSearchesService()->update($presentInDB['puuid'], $dbEntity, "puuid");
      Flight::json($responseJSON);
    } 
  }
  else{
//    var_dump("NOT IN DB; Using getSummonerMatches"); die;
    $responseJSON = Flight::riotService()->getSummonerMatches($summonerName, $region);
    $dbEntity = array();
    $dbEntity['profileIconId'] = $responseJSON['profileIconId'];
    $dbEntity['summonerLevel'] = $responseJSON['summonerLevel']; 
    $dbEntity['summonerName'] = $summonerName;
    $dbEntity['region'] = $region;
    $dbEntity['puuid'] = $responseJSON['puuid'];
    $dbEntity['encryptedSummonerId'] = $responseJSON['id'];
    $dbEntity['timeUpdated'] = new DateTime(date('Y-m-d H:i:s')); 
    $dbEntity['timeUpdated'] = $dbEntity['timeUpdated']->format('Y-m-d H:i:s');
    Flight::recentSearchesService()->add($dbEntity);
    Flight::json($responseJSON);
  }
});

/**
* @OA\Get(path="/summonersMobileAPI/{summonerName}/{region}", tags={"riot"},
*     @OA\Parameter(in="path", name="summonerName", example="Condemn for Stun", description="player's name"),
*     @OA\Parameter(in="path", name="region", example="eun1", description="player's server / region"),
*     @OA\Response(response="200", description="Fetch 5 simplified matches for player (used for mobile app)"),
*     @OA\Response(response="403", description="Forbidden... Incorrect API key / API key level not high enough"),
*     @OA\Response(response="404", description="Error: Data not found - summoner not found"),
*     @OA\Response(response="503", description="Service unavailable... RIOT API key outdated or RIOT API server fail")
* )
*/

Flight::route("GET /summonersMobileAPI/@summonerName/@region",  function($summonerName, $region){
   Flight::json(Flight::riotService()->getSummonerMatchesMobileAPI($summonerName, $region));
});
?>