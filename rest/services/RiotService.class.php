<?php
  class RiotService {

    var $headers = array(
      "Content-Type: text/html; charset=utf-8",
      "User-Agent: StatTrack",
      "Accept-Language: en-US,en;q=0.9",
      "Accept-Charset: application/x-www-form-urlencoded; charset=UTF-8",
      "Origin: https://developer.riotgames.com",
      "X-Riot-Token: RGAPI-a8911829-686e-49fc-8911-5bf7949393be"
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
    private function getSummonerInfo($summonerName, $region){
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

    private function getSummonerRanks($encryptedSummonerId, $region){
      $ch = curl_init();
      $url = 'https://' . $region . '.api.riotgames.com/lol/league/v4/entries/by-summoner/' . $encryptedSummonerId;
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      //return $response;
      $json = json_decode($response, true);
      $this->checkFor429Error($json);
      //return $json;
      return array('RANKED_FLEX_SR' => array('tier' => $json[0]['tier'], 'rank' => $json[0]['rank'], 'wins' => $json[0]['wins'], 'losses' => $json[0]['losses']), 
      'RANKED_SOLO_5x5' => array('tier' => $json[1]['tier'],'rank' => $json[1]['rank'],'wins' => $json[1]['wins'], 'losses' => $json[1]['losses']));
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

            if (($info['participants'][$i]['teamId'] == 100) && ($info['teams']['0']['win'] == true)) $returnVal['win'] = "true";
            else if (($info['participants'][$i]['teamId'] == 200) && ($info['teams']['1']['win'] == true)) $returnVal['win'] = "true";
            else $returnVal['win'] = "false";
        }}
        $returnVal['participants'][$i]['summonerName'] = $info['participants'][$i]['summonerName'];
        $returnVal['participants'][$i]['puuid'] = $info['participants'][$i]['puuid'];
        $returnVal['participants'][$i]['champLevel'] = $info['participants'][$i]['champLevel'];
        $returnVal['participants'][$i]['championName'] = $info['participants'][$i]['championName'];
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
        $i++;
      }
      return $returnVal;
    }

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
      $itemsDestroyed = array('0' => [], '1' => [], '2' => [], 
      '3' => [], '4' => [], '5' => [],
      '6' => [], '7' => [],'8' => [], '9' => []);
      $finalItems = array('0' => [array(),array()], '1' => [array(), array()], '2' => [array(),array()], 
      '3' => [array(),array()], '4' => [array(),array()], '5' => [array(),array()],
      '6' => [array(),array()], '7' => [array(),array()],'8' => [array(),array()], '9' => [array(),array()]);
      while($i<$matchLength+2){
        //$tempArray = array('itemId' => [], 'participantId' => []);
        foreach($json['info']['frames'][$i]['events'] as $eventIndex => $event){
          //return $event;
          if(isset($event['itemId'])){
            //array_push($itemsAdded[$event['participantId']-1], $event);
            if(strcmp($event['type'], "ITEM_PURCHASED")==0){
              if(($event['itemId'] > 3000) && !in_array($event['itemId'], $componentItems)) array_push($itemsAdded[$event['participantId']-1], $event['itemId']);
            } 
            //else if((strcmp($event['type'], "ITEM_DESTROYED")==0) || (strcmp($event['type'], "ITEM_UNDO")==0) || (strcmp($event['type'], "ITEM_SOLD") == 0)){
            //else if(strcmp($event['type'], "ITEM_DESTROYED")==0){
            //  else if($event['type'] == "ITEM_DESTROYED"){
              //array_push($itemsAdded[$event['participantId']-1], $event);
              
              //array_push($itemsDestroyed[$event['participantId']-1], $event['itemId']);

              //array_push($itemsDestroyed, $tempArray);
              //array_push($itemsDestroyed[$countDestroyed], $event['itemId']);
              //array_push($itemsDestroyed[$countDestroyed], $event['participantId']);
              //$countDestroyed++;
           // }
            //print_r($itemsAdded); die;
          }
          
          
          //return $itemsAdded;
          //return $event;
          
          //array_push($itemsAdded['participantId'], $event['participantId']);
          //return $itemsAdded;
          //$itemsAdded[$i]['itemId'] = $event['itemId'];
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


    // DINO KECO
    public function getSummonerMatches($summonerName, $region){
      //if(strlen($summonerName) == 0) $summonerName = "!";
      //if($region == "Server") $region = "eun1";

      if($region == "na1"){
        $continent = "americas";
      }
      else{
        $continent = "europe";
      }
      
      $summoner = $this->getSummonerInfo($summonerName, $region);
      
      $summoner['ranks'] = $this->getSummonerRanks($summoner['id'], $region);
      $summoner['matches'] = $this->getSummonerMatchesPrivate($summoner['puuid'], $continent);
      foreach($summoner['matches'] as $i => $match){
        $summoner['matches'][$i] = $this->getMatchInfo($match, $continent, $summoner['puuid']);
        $summoner['matches'][$i]['items'] = $this->getMatchItems($match, $continent, (int)$summoner['matches'][$i]['info']['matchLength']);

      }
      return $summoner;
      //exclude useless info
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

