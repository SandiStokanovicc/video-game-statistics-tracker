<?php

/**
* @OA\Get(path="/summoners/{summonerName}/{region}", tags={"riot"}, security={{"ApiKeyAuth": {}}},
*     @OA\Parameter(in="path", name="summonerName", example="Condemn for Stun", description="player's name"),
*     @OA\Parameter(in="path", name="region", example="eun1", description="player's server / region"),
*     @OA\Response(response="200", description="Fetch last 5 matches for player")
* )
*/

Flight::route('GET /summoners/@summonerName/@region', function($summonerName, $region){ 
   //trenutno se poziva sa 
   //http://localhost/video-game-statistics-tracker/Backend/code/rest/summoners/Condemn%20for%20Stun/eun1
   //Flight::json(Flight::riotService()->getSummonerInfo($summonerName, $region));
   Flight::json(Flight::riotService()->getSummonerMatches($summonerName, $region));
});

Flight::route("GET /summonersMobileAPI/@summonerName/@region",  function($summonerName, $region){
   Flight::json(Flight::riotService()->getSummonerMatchesMobileAPI($summonerName, $region));
});

Flight:route("GET /summonersMobileApiIcon/@summonerName/@region", function($summonerName, $region){
  Flight:json(Flight::riotService()->getSummonerInfo());
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

