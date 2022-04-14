<?php
Flight::route('/summoner/@summonerName', function($summonerName){ // goes to "localhost/video-game-statistics-tracker/index.php/"
  RiotService.getSummonerInfo($SummonerName);
  Flight::json(Flight::riotService()->getSummonerInfo());
});

Flight::route('POST /getMatches@puuid@continent', function($puuid, $continent){
  Flight::json(Flight::riotService()->getMatchIDs($puuid, $continent));
});
 ?>
