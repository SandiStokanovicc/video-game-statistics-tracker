<?php
  class RiotService {

    var $headers = array(
      "Content-Type: text/html; charset=utf-8",
      "User-Agent: StatTrack",
      "Accept-Language: en-US,en;q=0.9",
      "Accept-Charset: application/x-www-form-urlencoded; charset=UTF-8",
      "Origin: https://developer.riotgames.com",
<<<<<<< HEAD
      "X-Riot-Token: RGAPI-715f08ff-eee3-4cea-a317-ca3182a68df7" //RGAPI-a7e9b419-1c1f-441d-bfb5-808ff03d956a
=======
      "X-Riot-Token: RGAPI-cb93e7bb-2bd9-4616-aa9b-4f38bf823829"
>>>>>>> 759f1791038dc2d688fff5267ccda0663cdfe412
    );
    
    private function setCurlOptions($ch, $url){
      curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }

    private function checkFor429Error($json){
      
      if(!isset($json['status'])) return;
      else if($json['status']['status_code'] == 429){
        $httpStatusCode = 429;
        $httpStatusMsg  = 'Rate limit exceeded';
        $phpSapiName    = substr(php_sapi_name(), 0, 3);
        if ($phpSapiName == 'cgi' || $phpSapiName == 'fpm') {
          Flight::json(["message" => $httpStatusMsg]);
          die(header('Status: '.$httpStatusCode.' '.$httpStatusMsg));
        } else {
          Flight::json(["message" => $httpStatusMsg]);
          $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
          die(header($protocol.' '.$httpStatusCode.' '.$httpStatusMsg));
        }
      }
      else{
        $httpStatusCode = $json['status']['status_code'];
        $httpStatusMsg  = $json['status']['message'];
        $phpSapiName    = substr(php_sapi_name(), 0, 3);
        if ($phpSapiName == 'cgi' || $phpSapiName == 'fpm') {
          Flight::json(["message", $httpStatusMsg]);
          die(header('Status: '.$httpStatusCode.' '. $httpStatusMsg));
        } else {
          Flight::json(["message" => $httpStatusMsg]);
          $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
          die(header($protocol.' '.$httpStatusCode.' '.$httpStatusMsg));
        }
      } 
    }
    
    private function setContinent($region){
      if($region == "na1"){
        return "americas";
      }
      else{
        return "europe";
      }
    }
    
    // NEW STUFF THAT PRINTS FILTERED INFORMATION
    public function getSummonerInfo($summonerName, $region){
      $summonerName = str_replace(" ", "%20", $summonerName); // space replaced with "%20" for GET method. Doesn't work otherwise
      $summonerName = htmlspecialchars($summonerName); // replaces < with &lt, > with &gt, etc. for avoiding XSS attacks

      $ch = curl_init(); // initialize cURL_PHP connection
      $url = 'https://' . $region .'.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $summonerName;
     
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch); // get results
      curl_close($ch); // close connection

      $json = json_decode($response, true); // transform result from JSON (or whatever) into array
      $this->checkFor429Error($json);

      return array('id' => $json['id'], 'name' => $json['name'], 'puuid' => $json['puuid'], 'profileIconId' => $json['profileIconId'], 'summonerLevel' => $json['summonerLevel']);
    }

    private function getSummonerMatchesPrivate($puuid, $continent){
      $ch = curl_init();
      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $puuid . '/ids?start=0&count=5&type=ranked';
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      $this->checkFor429Error($json);
      return $json;
    }

    private function getSummonerRanks($encryptedSummonerId, $region){
      $ch = curl_init();
      $url = 'https://' . $region . '.api.riotgames.com/lol/league/v4/entries/by-summoner/' . $encryptedSummonerId;
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      $this->checkFor429Error($json);

      if(empty($json)){
        return array(0 => array('queueType' => 'RANKED_FLEX_SR', 'tier' => "", 'rank' => "UNRANKED", 'wins' => 0, 'losses' => 0), 
        1 => array('queueType' => 'RANKED_SOLO_5x5', 'tier' => "",'rank' => "UNRANKED",'wins' => 0, 'losses' => 0));  
      }
      else if(count($json) == 1 && $json[0]['queueType'] == "RANKED_FLEX_SR"){
        return array(0 => array('queueType' => $json[0]['queueType'], 'tier' => $json[0]['tier'], 'rank' => $json[0]['rank'], 'wins' => $json[0]['wins'], 'losses' => $json[0]['losses']),
        1 => array('queueType' => 'RANKED_SOLO_5x5', 'tier' => "UNRANKED",'rank' => "I",'wins' => 0, 'losses' => 0));
      }
      else if(count($json) == 1 && $json[0]['queueType'] == "RANKED_SOLO_5x5"){
        return array(0 => array('queueType' => $json[0]['queueType'], 'tier' => $json[0]['tier'], 'rank' => $json[0]['rank'], 'wins' => $json[0]['wins'], 'losses' => $json[0]['losses']),
        1 => array('queueType' => 'RANKED_FLEX_SR', 'tier' => "UNRANKED",'rank' => "I",'wins' => 0, 'losses' => 0));
      }
      else {
        return array(0 => array('queueType' => $json[0]['queueType'], 'tier' => $json[0]['tier'], 'rank' => $json[0]['rank'], 'wins' => $json[0]['wins'], 'losses' => $json[0]['losses']),
        1 => array('queueType' => $json[1]['queueType'], 'tier' => $json[1]['tier'], 'rank' => $json[1]['rank'], 'wins' => $json[1]['wins'], 'losses' => $json[1]['losses']));
      }
     }

    private function getLiveMatchInfo($encryptedSummonerId, $region){
      $ch = curl_init();
      //https://eun1.api.riotgames.com/lol/spectator/v4/active-games/by-summoner/jAcOJoArjCtbg1CiwxGI01MgIZE80tCQc12UCJPYdEI2faw
      $url = 'https://' . $region . '.api.riotgames.com/lol/spectator/v4/active-games/by-summoner/' . $encryptedSummonerId;
      $this->setCurlOptions($ch, $url);
      $response = curl_exec($ch);
      curl_close($ch);

      $json = json_decode($response, true);

      if(isset($json['status'])) return array('IsInMatch' => false);
      
      $participants = array();
      $i = 0;
      while($i<10){
        $participants[$i] = array('summonerName' => $json['participants'][$i]['summonerName'], 'championId' => $json['participants'][$i]['championId'],
        'summonerSpell1Id' => $json['participants'][$i]['spell1Id'], 'summonerSpell2Id' => $json['participants'][$i]['spell2Id']);
        $i++;
      }

      $bannedChampions = array();
      $i = 0;
      if(!empty($json['bannedChampions'])){
        while($i<10){
          $bannedChampions[$i] = $json['bannedChampions'][$i]['championId'];
          $i++;
        }
      }
      return array('IsInMatch' => true, 'participants' => $participants, 'gameStartTime' => round($json['gameStartTime']/1000/60,2), 
      'gameLength' => round($json['gameLength'] / 60,2), 'bannedChampions' => $bannedChampions);
    }
  
    private function getMatchInfo($matchId, $continent, $mainPlayerPuuid){
      $ch = curl_init();
      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/' . $matchId;
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      $this->checkFor429Error($json);
      return $json = $this->filterInfo($json['info'], $mainPlayerPuuid);
    }
    
    private function filterInfo($info, $mainPlayerPuuid){
      $parts = $this->filterParticipants($info, $mainPlayerPuuid);
      return array('info' => array('searchedPlayerInfo' => $parts['searchedPlayerInfo'], 'participants' => $parts['participants'], 'win' => $parts['win'],
      'matchLength' => (round(($info['gameEndTimestamp']-$info['gameStartTimestamp'])/1000/60,2)), 'playedBefore' => (int)(time() - $info['gameStartTimestamp'] / 1000)));
    }

    private function filterParticipants($info, $mainPlayerPuuid){
      $foundPlayer = "false";
      $returnVal = array('win'=>" ", 'searchedPlayerInfo' => array('kills' => 0, 'deaths' => 0, 'assists' => 0, 'championId' => 0), 'participants' => array('0' => [], '1' => [], '2' => [], 
      '3' => [], '4' => [], '5' => [],
      '6' => [], '7' => [],'8' => [], '9' => []));
      $i = 0;
      while($i<10){
        if($foundPlayer == "false"){
          if($info['participants'][$i]['puuid'] == $mainPlayerPuuid){
            $foundPlayer = true;
            $returnVal['searchedPlayerInfo']['kills'] = $info['participants'][$i]['kills'];
            $returnVal['searchedPlayerInfo']['deaths'] = $info['participants'][$i]['deaths'];
            $returnVal['searchedPlayerInfo']['assists'] = $info['participants'][$i]['assists'];
            $returnVal['searchedPlayerInfo']['championName'] = $info['participants'][$i]['championName'];
            $returnVal['searchedPlayerInfo']['championId'] = $info['participants'][$i]['championId'];

            if (($info['participants'][$i]['teamId'] == 100) && ($info['teams']['0']['win'] == true)) $returnVal['win'] = "true";
            else if (($info['participants'][$i]['teamId'] == 200) && ($info['teams']['1']['win'] == true)) $returnVal['win'] = "true";
            else $returnVal['win'] = "false";
        }}
        $returnVal['participants'][$i]['summonerName'] = $info['participants'][$i]['summonerName'];
        $returnVal['participants'][$i]['puuid'] = $info['participants'][$i]['puuid'];
        $returnVal['participants'][$i]['champLevel'] = $info['participants'][$i]['champLevel'];
        $returnVal['participants'][$i]['championName'] = $info['participants'][$i]['championName'];
        $returnVal['participants'][$i]['championId'] = $info['participants'][$i]['championId'];
        $returnVal['participants'][$i]['summoner1Id'] = $info['participants'][$i]['summoner1Id'];
        $returnVal['participants'][$i]['summoner2Id'] = $info['participants'][$i]['summoner2Id'];
        $returnVal['participants'][$i]['kills'] = $info['participants'][$i]['kills'];
        $returnVal['participants'][$i]['deaths'] = $info['participants'][$i]['deaths'];
        $returnVal['participants'][$i]['assists'] = $info['participants'][$i]['assists'];
        $returnVal['participants'][$i]['kda'] = round($info['participants'][$i]['challenges']['kda'], 2);
        $returnVal['participants'][$i]['controlWardsPlaced'] = $info['participants'][$i]['challenges']['controlWardsPlaced'];
        $returnVal['participants'][$i]['wardsPlaced'] = $info['participants'][$i]['wardsPlaced'];
        $returnVal['participants'][$i]['wardsKilled'] = $info['participants'][$i]['wardsKilled'];
        $returnVal['participants'][$i]['totalDamageDealtToChampions'] = $info['participants'][$i]['totalDamageDealtToChampions'];
        $returnVal['participants'][$i]['totalDamageTaken'] = $info['participants'][$i]['totalDamageTaken'];
        $returnVal['participants'][$i]['totalMinionsKilled'] = $info['participants'][$i]['totalMinionsKilled'] + $info['participants'][$i]['neutralMinionsKilled'];
        $returnVal['participants'][$i]['item0'] = $info['participants'][$i]['item0'];
        $returnVal['participants'][$i]['item1'] = $info['participants'][$i]['item1'];
        $returnVal['participants'][$i]['item2'] = $info['participants'][$i]['item2'];
        $returnVal['participants'][$i]['item3'] = $info['participants'][$i]['item3'];
        $returnVal['participants'][$i]['item4'] = $info['participants'][$i]['item4'];
        $returnVal['participants'][$i]['item5'] = $info['participants'][$i]['item5'];
        $returnVal['participants'][$i]['item6'] = $info['participants'][$i]['item6'];
        $i++;
      }
      return $returnVal;
    }

    // MAIN FUNCTION
    public function getSummonerMatches($summonerName, $region){
      $continent = $this->setContinent($region);
      $summoner = array();

      $summoner = $this->getSummonerInfo($summonerName, $region);
      
      $summoner['ranks'] = $this->getSummonerRanks($summoner['id'], $region);
      $summoner['matchIDs'] = $this->getSummonerMatchesPrivate($summoner['puuid'], $continent);
      $summoner['liveMatch'] = $this->getLiveMatchInfo($summoner['id'], $region);
      foreach($summoner['matchIDs'] as $i => $match){
        $summoner['matches'][$i] = $this->getMatchInfo($match, $continent, $summoner['puuid']);
      }
      return $summoner;
    }

    //called from FavouriteMatchService.class.php
    public function getFavouriteMatches($favouriteMatches){
      $summoner = array('matches' => array(), 'matchIDs' => array());
      foreach($favouriteMatches as $i => $match){
        $summoner['matches'][$i] = $this->getMatchInfo($match['APIMatchID'], $match['continent'], $match['mainPlayerPUUID']);
        array_push($summoner['matchIDs'], $match['APIMatchID']);
      }
      return $summoner;
    }
    
    //for those that have already been searched
    public function getRecentSummonerMatches($dbEntity){
      $continent = $this->setContinent($dbEntity['region']);
      $summoner = array('id' => $dbEntity['encryptedSummonerId'], 'name' => $dbEntity['summonerName'], 'puuid' => $dbEntity['puuid'], 
      'profileIconId' => $dbEntity['profileIconId'], 'summonerLevel' => $dbEntity['summonerLevel']);
      $summoner['ranks'] = $this->getSummonerRanks($dbEntity['encryptedSummonerId'], $dbEntity['region']); 
      $summoner['matchIDs'] = $this->getSummonerMatchesPrivate($dbEntity['puuid'], $continent);
      $summoner['liveMatch'] = $this->getLiveMatchInfo($dbEntity['encryptedSummonerId'], $dbEntity['region']);

      foreach($summoner['matchIDs'] as $i => $match){
        $summoner['matches'][$i] = $this->getMatchInfo($match, $continent, $dbEntity['puuid']);
      }
      return $summoner;
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //route used as an external API for our mobile application
    public function getSummonerMatchesMobileAPI($summonerName, $region){
      $continent = $this->setContinent($region);
      
      $summoner = $this->getSummonerInfo($summonerName, $region);
      $summoner['matches'] = $this->getSummonerMatchesPrivate($summoner['puuid'], $continent);
      foreach($summoner['matches'] as $i => $match){
        $summoner['matches'][$i] = $this->getMatchInfoMobileAPI($match, $continent, $summoner['puuid']);
      }
      return $summoner;
    }

    private function getMatchInfoMobileAPI($matchId, $continent, $mainPlayerPuuid){
      $ch = curl_init();
      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/' . $matchId;
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      $this->checkFor429Error($json);
      return $json = $this->filterInfoMobileAPI($json['info'], $mainPlayerPuuid);
    }
    
    private function filterInfoMobileAPI($info, $mainPlayerPuuid){
      $parts = $this->filterParticipantsMobileAPI($info, $mainPlayerPuuid);
      return $parts;
    }
    
    private function filterParticipantsMobileAPI($info, $mainPlayerPuuid){
      $foundPlayer = "false";
      $returnVal = array('championIcon' => 0,'kda' => 0, 'matchResult' => " ", 'killsDeathsAssists' => 0, 
      'controlWardsPlaced' => 0, 'wardsKilled' => 0, 'wardsPlaced' => 0, 'damageDealt' => 0, 'damageTaken' => 0,
      'minionsKilled' => 0, 'champLevel' => 0, 'summonerSpell1Id' => 0, 'summonerSpell2Id' => 0, 'item0' => 0, 
      'item1' => 0, 'item2' => 0, 'item3' => 0, 'item4' => 0, 'item5' => 0, 'item6' => 0);
      $i = 0;
      while($i<10){
        if($foundPlayer == "false"){
          if($info['participants'][$i]['puuid'] == $mainPlayerPuuid){
            $foundPlayer = true;
            $returnVal['championIcon'] = $info['participants'][$i]['championName'];
            $returnVal['kda'] = round($info['participants'][$i]['challenges']['kda'], 2);
            $returnVal['killsDeathsAssists'] = $info['participants'][$i]['kills'] . "/" . $info['participants'][$i]['deaths'] .
            "/" . $info['participants'][$i]['assists'];
            $returnVal['controlWardsPlaced'] = $info['participants'][$i]['challenges']['controlWardsPlaced'];
            $returnVal['wardsKilled'] = $info['participants'][$i]['wardsKilled'];
            $returnVal['wardsPlaced'] = $info['participants'][$i]['wardsPlaced'];
            $returnVal['damageDealt'] = $info['participants'][$i]['totalDamageDealtToChampions'];
            $returnVal['damageTaken'] = $info['participants'][$i]['totalDamageTaken'];
            $returnVal['minionsKilled'] = $info['participants'][$i]['totalMinionsKilled'] + 
            $info['participants'][$i]['neutralMinionsKilled'];
            $returnVal['champLevel'] = $info['participants'][$i]['champLevel'];
            $returnVal['summonerSpell1Id'] = $info['participants'][$i]['summoner1Id'];
            $returnVal['summonerSpell2Id'] = $info['participants'][$i]['summoner2Id'];
            $returnVal['item0'] = $info['participants'][$i]['item0'];
            $returnVal['item1'] = $info['participants'][$i]['item1'];
            $returnVal['item2'] = $info['participants'][$i]['item2'];
            $returnVal['item3'] = $info['participants'][$i]['item3'];
            $returnVal['item4'] = $info['participants'][$i]['item4'];
            $returnVal['item5'] = $info['participants'][$i]['item5'];
            $returnVal['item6'] = $info['participants'][$i]['item6'];
            if (($info['participants'][$i]['teamId'] == 100) && ($info['teams']['0']['win'] == true)) $returnVal['matchResult'] = "Victory";
            else if (($info['participants'][$i]['teamId'] == 200) && ($info['teams']['1']['win'] == true)) $returnVal['matchResult'] = "Victory";
            else $returnVal['matchResult'] = "Defeat";
            return $returnVal;
        }}
        $i++;
      }
      return $returnVal;
    }
}
?>