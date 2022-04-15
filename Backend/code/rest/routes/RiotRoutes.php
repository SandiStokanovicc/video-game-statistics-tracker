<?php
Flight::route('/', function(){
  echo "Located in index.php";
});

Flight::route('GET /summoners/@summonerName/@region', function($summonerName, $region){ 
  // trenutno se poziva sa 
  // http://localhost/video-game-statistics-tracker/Backend/code/rest/summoners/Condemn%20for%20Stun/eun1
  Flight::json(Flight::riotService()->getSummonerInfo($summonerName, $region));
});

Flight::route('POST /matches/@puuid/@continent', function($puuid, $continent){
  Flight::json(Flight::riotService()->getMatchIDs($puuid, $continent));
});
?>
