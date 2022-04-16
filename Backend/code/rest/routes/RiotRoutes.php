<?php
Flight::route('/', function(){
  echo "Located in index.php";
});

Flight::route('GET /summoners/@summonerName/@region', function($summonerName, $region){ 
  // trenutno se poziva sa 
  // http://localhost/video-game-statistics-tracker/Backend/code/rest/summoners/Condemn%20for%20Stun/eun1
  Flight::json(Flight::riotService()->getSummonerInfo($summonerName, $region));
});

/* //ne moze na ovaj nacin jer je post. Treba proslijediti samo request()->data->getData()
Flight::route('POST /matches/@puuid/@continent', function($puuid, $continent){
  Flight::json(Flight::riotService()->getMatchIDs($puuid, $continent));
});
*/

Flight::route('POST /matches', function(){
  Flight::json(Flight::riotService()->getMatchIDs(Flight::request()->data->getData()));
});

Flight::route('GET /matches/@matchId', function($matchId){
  Flight::json(Flight::riotService()->getMatchById($matchId));
});
?>
