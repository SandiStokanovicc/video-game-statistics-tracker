<?php

/**
* @OA\Get(path="/summoners/{summonerName}/{region}", tags={"riot"}, security={{"ApiKeyAuth": {}}},
*     @OA\Parameter(in="path", name="summonerName", example="Condemn for Stun", description="player's name"),
*     @OA\Parameter(in="path", name="region", example="eun1", description="player's server / region"),
*     @OA\Response(response="200", description="Fetch last 5 matches for player")
* )
*/

Flight::route('GET /summoners/@summonerName/@region', function($summonerName, $region){ 
  //var_dump($data['userId']); die;
  //$summonerName = strtolower(str_replace(' ', '', $summonerName));
  //var_dump($summonerName); die;
  $presentInDB = Flight::recentSearchesService()->getSummonerNameRegion($summonerName, $region);
  if(!empty($presentInDB)){
    //var_dump("using getRecentSummonerMatches"); die;
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
    //var_dump("using getSummonerMatches"); die;
    Flight::json($responseJSON);
  }
});

Flight::route("GET /summonersMobileAPI/@summonerName/@region",  function($summonerName, $region){
   Flight::json(Flight::riotService()->getSummonerMatchesMobileAPI($summonerName, $region));
});



//Flight::route('GET /summoners/@summonerName/@region', function($summonerName, $region){ 
  //Flight::json(Flight::riotService()->getMatchBySummoner($summonerName, $region));
//});

/* //ne moze na ovaj nacin jer je post. Treba proslijediti samo request()->data->getData()
Flight::route('POST /matches/@puuid/@continent', function($puuid, $continent){
  Flight::json(Flight::riotService()->getMatchIDs($puuid, $continent));
});
*/

//staro
/*
Flight::route('POST /matches', function(){
  Flight::json(Flight::riotService()->getMatchIDs(Flight::request()->data->getData()));
});

Flight::route('GET /matches/@matchId/@continent', function($matchId, $continent){
  Flight::json(Flight::riotService()->getMatchById($matchId, $continent));
});
*/

