<?php
  class RiotService {

    var $headers = array(
      "Content-Type: text/html; charset=utf-8",
      "User-Agent: StatTrack",
      "Accept-Language: en-US,en;q=0.9",
      "Accept-Charset: application/x-www-form-urlencoded; charset=UTF-8",
      "Origin: https://developer.riotgames.com",
      "X-Riot-Token: RGAPI-a7e9b419-1c1f-441d-bfb5-808ff03d956a" //RGAPI-a7e9b419-1c1f-441d-bfb5-808ff03d956a
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
            die(header('Status: '.$httpStatusCode.' '.$httpStatusMsg));
        } else {
            $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
            die(header($protocol.' '.$httpStatusCode.' '.$httpStatusMsg));
        }
      }
      else{
        $httpStatusCode = $json['status']['status_code'];
        $httpStatusMsg  = $json['status']['message'];
        $phpSapiName    = substr(php_sapi_name(), 0, 3);
        if ($phpSapiName == 'cgi' || $phpSapiName == 'fpm') {
            die(header('Status: '.$httpStatusCode.' '. $httpStatusMsg));
        } else {
            $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
            die(header($protocol.' '.$httpStatusCode.' '.$httpStatusMsg));
        }
      } 
    }
    
    // NEW STUFF THAT PRINTS FILTERED INFORMATION
    public function getSummonerInfo($summonerName, $region){
      $summonerName = str_replace(" ", "%20", $summonerName); // space replaced with "%20" for GET method. Doesn't work otherwise
      $summonerName = htmlspecialchars($summonerName); // replaces < with &lt, > with &gt, etc. for avoiding XSS attacks
      //$region = "eun1";

      $ch = curl_init(); // initialize cURL_PHP connection
      $url = 'https://' . $region .'.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $summonerName;
     

      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch); // get results
      curl_close($ch); // close connection

      $json = json_decode($response, true); // transform result from JSON (or whatever) into array

      $this->checkFor429Error($json);
      //return $json;

      // or 
      return array('id' => $json['id'], 'name' => $json['name'], 'puuid' => $json['puuid'], 'profileIconId' => $json['profileIconId'], 'summonerLevel' => $json['summonerLevel']);
    }

    private function getSummonerMatchesPrivate($puuid, $continent){
      $ch = curl_init();

      //$puuid je path parameter (mora biti unutar url-a), dok su $start i $count query params (optional)
      //$start = 0;
      //$count = 10;
      //$url = 'https://' . $requestData['continent'] . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $requestData['puuid'] . '/ids?start=0' . $start . '&count=10' . $count;

      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $puuid . '/ids?start=0&count=5&type=ranked';

      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      $this->checkFor429Error($json);
      return $json;
    }

    // error with unranked players
    private function getSummonerRanks($encryptedSummonerId, $region){
      $ch = curl_init();
      $url = 'https://' . $region . '.api.riotgames.com/lol/league/v4/entries/by-summoner/' . $encryptedSummonerId;
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      //return $response;
      $json = json_decode($response, true);
      $this->checkFor429Error($json);
      //return $json;
      
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
      /*
      else if((count($json) == 1) && $json[0]['queueType'] == "RANKED_FLEX_SR"){
        return array('RANKED_FLEX_SR' => array('tier' => $json[0]['tier'], 'rank' => $json[0]['rank'], 
        'wins' => $json[0]['wins'], 'losses' => $json[0]['losses']), 'RANKED_SOLO_5x5' => array('tier' => "", 'rank' => "UNRANKED", 'wins' => 0, 'losses' => 0));
      }
      else if((count($json) == 1) && $json[0]['queueType'] == "RANKED_SOLO_5x5"){
        return array(0 => 'RANKED_SOLO_5x5' => array('tier' => $json[0]['tier'], 'rank' => $json[0]['rank'], 
        'wins' => $json[0]['wins'], 'losses' => $json[0]['losses']), 'RANKED_FLEX_SR' => array('tier' => "", 'rank' => "UNRANKED", 'wins' => 0, 'losses' => 0));
      }
      */
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
      //return $info['participants'] = $this->filterParticipants($info['participants']);
      $parts = $this->filterParticipants($info, $mainPlayerPuuid);

      //return array('info' => array('participants' => $parts));
      //return $info = filterParticipants($info['participants']);
      return array('info' => array('searchedPlayerInfo' => $parts['searchedPlayerInfo'], 'participants' => $parts['participants'], 'win' => $parts['win'],
      'matchLength' => (round(($info['gameEndTimestamp']-$info['gameStartTimestamp'])/1000/60,2)), 'playedBefore' => (int)(time() - $info['gameStartTimestamp'] / 1000)));
    }

    private function filterParticipants($info, $mainPlayerPuuid){
      $foundPlayer = "false";
      //$returnVal = array('summonerName' => array(), 'champLevel' => array());
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

    // BELOW METHOD IS NO LONGER IN USE!!!!
    /*
    private function getMatchItems($matchId, $continent, $matchLength){
      $ch = curl_init();
      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/' . $matchId . '/timeline';
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      $this->checkFor429Error($json);

      $componentItems = array(3044, 3191, 3051, 3057, 3066, 3067, 3070, 3076, 3077, 3082, 3086, 3108, 3112, 3113, 3114, 3123, 3133, 3134, 3140,
       3145, 3155, 3340, 3363, 3364, 3400, 3802, 3850, 3851, 3854, 3855, 3858, 3859, 3862, 3863, 3916, 4630, 6670, 6660);

      //return $json;

      $i = 0;
      $itemsAdded = array('0' => array(), '1' => array(), 
      '2' => array(), '3' => array(), '4' => array(), '5' => array(),
      '6' => array(), '7' => array(),'8' => array(), '9' => array());
      while($i<$matchLength+2){
        //$tempArray = array('itemId' => [], 'participantId' => []);
        foreach($json['info']['frames'][$i]['events'] as $eventIndex => $event){
          //return $event;
          if(isset($event['itemId'])){
            //array_push($itemsAdded[$event['participantId']-1], $event);
            if(strcmp($event['type'], "ITEM_PURCHASED")==0){
              if(($event['itemId'] > 3000) && !in_array($event['itemId'], $componentItems)) array_push($itemsAdded[$event['participantId']-1], $event['itemId']);
            } 
          }
        }
        
        
          
        $i++;
        
        //return $itemsAdded;
        //$itemsAdded[$json['info']['frames'][$i]['events']]
      }
  //    $j = 0;
        //while($j<10){
          //$finalItems[$j][0] = $itemsAdded[$j];
          //$finalItems[$j][1] = $itemsDestroyed[$j];
          //$itemsAdded[$j] = array_unique($itemsAdded[$j]); //remove duplicates
         // $j++;
      // }
        //return $finalItems;
      return $itemsAdded;
    }
*/

    // MAIN FUNCTION
    public function getSummonerMatches($summonerName, $region){
      //if(strlen($summonerName) == 0) $summonerName = "!";
      //if($region == "Server") $region = "eun1";

      if($region == "na1"){
        $continent = "americas";
      }
      else{
        $continent = "europe";
      }
      $summoner = array();

      $summoner = $this->getSummonerInfo($summonerName, $region);
      
      $summoner['ranks'] = $this->getSummonerRanks($summoner['id'], $region);
      $summoner['matchIDs'] = $this->getSummonerMatchesPrivate($summoner['puuid'], $continent);
      $summoner['liveMatch'] = $this->getLiveMatchInfo($summoner['id'], $region);
      foreach($summoner['matchIDs'] as $i => $match){
        $summoner['matches'][$i] = $this->getMatchInfo($match, $continent, $summoner['puuid']);
        //$summoner['matches'][$i]['items'] = $this->getMatchItems($match, $continent, (int)$summoner['matches'][$i]['info']['matchLength']);

      }
      //var_dump($summoner); die;
      return $summoner;
      //exclude useless info
    }

    //called from FavouriteMatchService.class.php
    public function getFavouriteMatches($favouriteMatches){

      $summoner = array('matches' => array());
      foreach($favouriteMatches as $i => $match){
        $summoner['matches'][$i] = $this->getMatchInfo($match['APIMatchID'], $match['continent'], $match['mainPlayerPUUID']);
        //$summoner['matches'][$i]['items'] = $this->getMatchItems($match, $continent, (int)$summoner['matches'][$i]['info']['matchLength']);
      }
      return $summoner;
    }
    

    //for those that have already been searched
    public function getRecentSummonerMatches($dbEntity){
      if($dbEntity['region'] == "na1") $continent = "americas";
      else $continent = "europe";

      $summoner = array('id' => $dbEntity['encryptedSummonerId'], 'name' => $dbEntity['summonerName'], 'puuid' => $dbEntity['puuid'], 
      'profileIconId' => $dbEntity['profileIconId'], 'summonerLevel' => $dbEntity['summonerLevel']);
      $summoner['ranks'] = $this->getSummonerRanks($dbEntity['encryptedSummonerId'], $dbEntity['region']); 
      $summoner['matchIDs'] = $this->getSummonerMatchesPrivate($dbEntity['puuid'], $continent);
      $summoner['liveMatch'] = $this->getLiveMatchInfo($dbEntity['encryptedSummonerId'], $dbEntity['region']);

      foreach($summoner['matchIDs'] as $i => $match){
        $summoner['matches'][$i] = $this->getMatchInfo($match, $continent, $dbEntity['puuid']);
        //$summoner['matches'][$i]['items'] = $this->getMatchItems($match, $continent, (int)$summoner['matches'][$i]['info']['matchLength']);
      }
      //var_dump($summoner);die;
      //$summoner = json_encode($summoner);
      return $summoner;
    }







///////////////////////////////////////////////////////////////////////////////




    public function getSummonerMatchesMobileAPI($summonerName, $region){
      //if(strlen($summonerName) == 0) $summonerName = "!";
      //if($region == "Server") $region = "eun1";

      if($region == "na1"){
        $continent = "americas";
      }
      else{
        $continent = "europe";
      }
      
      $summoner = $this->getSummonerInfo($summonerName, $region);
      
      //$summoner['ranks'] = $this->getSummonerRanks($summoner['id'], $region);
      $summoner['matches'] = $this->getSummonerMatchesPrivate($summoner['puuid'], $continent);
      foreach($summoner['matches'] as $i => $match){
        $summoner['matches'][$i] = $this->getMatchInfoMobileAPI($match, $continent, $summoner['puuid']);
        //$summoner['matches'][$i]['items'] = $this->getMatchItems($match, $continent, (int)$summoner['matches'][$i]['info']['matchLength']);

      }
      return $summoner;
      //exclude useless info
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
      //return $info['participants'] = $this->filterParticipants($info['participants']);
      $parts = $this->filterParticipantsMobileAPI($info, $mainPlayerPuuid);

      //return array('info' => array('participants' => $parts));
      //return $info = filterParticipants($info['participants']);
      return $parts;
      //return array('info' => array('searchedPlayerInfo' => $parts['searchedPlayerInfo'], 'participants' => $parts['participants'], 'win' => $parts['win'],
      //'matchLength' => (round(($info['gameEndTimestamp']-$info['gameStartTimestamp'])/1000/60,2)), 'playedBefore' => (int)(time() - $info['gameStartTimestamp'] / 1000)));
    }
    
    private function filterParticipantsMobileAPI($info, $mainPlayerPuuid){
      $foundPlayer = "false";
      //$returnVal = array('summonerName' => array(), 'champLevel' => array());
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














    /* //OLD STUFF THAT PRINTS EVERYTHING COMBINED INTO 1 JSON
    private function getSummonerInfo1($summonerName, $region){
      $summonerName = str_replace(" ", "%20", $summonerName); // space replaced with "%20" for GET method. Doesn't work otherwise
      $summonerName = htmlspecialchars($summonerName); // replaces < with &lt, > with &gt, etc. for avoiding XSS attacks
      //$region = "eun1";

      $ch = curl_init(); // initialize cURL_PHP connection
      $url = 'https://' . $region .'.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $summonerName;
     

      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch); // get results

      curl_close($ch); // close connection

      $json = json_decode($response, true); // transform result from JSON (or whatever) into array
      return $json;

      // or 
      // return array('name' => $json['name'], 'something' => $json['something']);
    }

    private function getSummonerMatchesPrivate($puuid, $continent){
      $ch = curl_init();

      //$puuid je path parameter (mora biti unutar url-a), dok su $start i $count query params (optional)
      //$start = 0;
      //$count = 10;
      //$url = 'https://' . $requestData['continent'] . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $requestData['puuid'] . '/ids?start=0' . $start . '&count=10' . $count;

      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $puuid . '/ids?start=0&count=2';

      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      return $json;
    }

    private function getMatchInfo($matchId, $continent){
      $ch = curl_init();
      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/' . $matchId;
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      return $json;
    }

    // DINO KECO
    public function getSummonerMatches($summonerName, $region){
      if($region == "na1"){
        $continent = "americas";
      }
      else{
        $continent = "europe";
      }
      $summoner = $this->getSummonerInfo1($summonerName, $region);
      $summoner['matches'] = $this->getSummonerMatchesPrivate($summoner['puuid'], $continent);
      foreach($summoner['matches'] as $i => $match){
        //$summoner['matches'][$i] = getMatchInfo($match['matchid']);
        $summoner['matches'][$i] = $this->getMatchInfo($match, $continent);
      }
      return $summoner;
      //exclude useless info
    }
    */
  

    /*
    public function getSummonerInfo($summonerName, $region){
      $summonerName = str_replace(" ", "%20", $summonerName); // space replaced with "%20" for GET method. Doesn't work otherwise
      $summonerName = htmlspecialchars($summonerName); // replaces < with &lt, > with &gt, etc. for avoiding XSS attacks
      //$region = "eun1";

      $ch = curl_init(); // initialize cURL_PHP connection
      $url = 'https://' . $region .'.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $summonerName;
     

      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch); // get results

      curl_close($ch); // close connection

      $json = json_decode($response, true); // transform result from JSON (or whatever) into array
      return $json;

    }

    
    public function getMatchIDs($requestData){
      $ch = curl_init();

      //$puuid je path parameter (mora biti unutar url-a), dok su $start i $count query params (optional)
      //$start = 0;
      //$count = 10;
      //$url = 'https://' . $requestData['continent'] . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $requestData['puuid'] . '/ids?start=0' . $start . '&count=10' . $count;

      $url = 'https://' . $requestData['continent'] . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $requestData['puuid'] . '/ids?start=0&count=1';

      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      return $json;
    }
  
    public function getMatchById($matchId, $continent){

    $ch = curl_init();
    $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/' . $matchId;
    $this->setCurlOptions($ch, $url);

    $response = curl_exec($ch);
    $json = json_decode($response, true);
    return $json;
  }*/
  












    /*
    public function getMatchBySummoner($summonerName, $region){
      $fullReturn = array();
      $summonerName = str_replace(" ", "%20", $summonerName); // space replaced with "%20" for GET method. Doesn't work otherwise
      $summonerName = htmlspecialchars($summonerName); // replaces < with &lt, > with &gt, etc. for avoiding XSS attacks
      //$region = "eun1";

      $ch = curl_init(); // initialize cURL_PHP connection
      $url = 'https://' . $region .'.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $summonerName;
     
      $this->setCurlOptions($ch, $url);

      $jsonSummonerInfo = curl_exec($ch); // get results

      $afullReturn = json_decode($response, true); // transform result from JSON (or whatever) into array
      
      if($region == "na1"){
        $continent = "americas";
      }
      else{
        $continent = "europe";
      }
//-----------------------------------------------------------------------------------------------------------

      $ch = curl_init();

      //$puuid je path parameter (mora biti unutar url-a), dok su $start i $count query params (optional)
      //$start = 0;
      //$count = 10;
      //$url = 'https://' . $requestData['continent'] . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $requestData['puuid'] . '/ids?start=0' . $start . '&count=10' . $count;
      $matches=array();
      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $jsonSummonerInfo['puuid'] . '/ids?start=0&count=2';

      $this->setCurlOptions($ch, $url);

      $responseMatches = curl_exec($ch);

      $SummonerAndMatchIDs = 
      return $responseMatches;
      //$jsonMatchIDs = json_decode($response, true);
      foreach($jsonMatchIDs as $matchId){

      //-----------------------------------------------------------------------------------------------------------
      $ch = curl_init();
      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/' . $matchId;
      $this->setCurlOptions($ch, $url);
  
      $responseSpecificMatch = curl_exec($ch);
      array_merge($matches,json_decode($response, true));
    }
    
      curl_close($ch); // close connection
      return $matches;

      //$json = json_decode($response, true);
      //return $json;    
      }

      public function returnMatches($response){
        return $response;

      }
  */
}

