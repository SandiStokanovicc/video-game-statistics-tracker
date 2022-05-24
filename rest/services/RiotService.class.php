<?php
  class RiotService {

    var $headers = array(
      "Content-Type: text/html; charset=utf-8",
      "User-Agent: StatTrack",
      "Accept-Language: en-US,en;q=0.9",
      "Accept-Charset: application/x-www-form-urlencoded; charset=UTF-8",
      "Origin: https://developer.riotgames.com",
      "X-Riot-Token: RGAPI-d14a97a1-86d9-483d-92e3-60216a91e8db"
    );
    
    private function setCurlOptions($ch, $url){
      curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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

      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $puuid . '/ids?start=0&count=2&type=ranked';

      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      return $json;
    }

    private function getSummonerRanks($encryptedSummonerId, $region){
      $ch = curl_init();
      $url = 'https://' . $region . '.api.riotgames.com/lol/league/v4/entries/by-summoner/' . $encryptedSummonerId;
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      //return $response;
      $json = json_decode($response, true);
      //return $json;
      return array('RANKED_FLEX_SR' => array('tier' => $json[0]['tier'], 'rank' => $json[0]['rank'], 'wins' => $json[0]['wins'], 'losses' => $json[0]['losses']), 
      'RANKED_SOLO_5x5' => array('tier' => $json[1]['tier'],'rank' => $json[1]['rank'],'wins' => $json[1]['wins'], 'losses' => $json[1]['losses']));
    }

    private function getMatchInfo($matchId, $continent){
      $ch = curl_init();
      $url = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/' . $matchId;
      $this->setCurlOptions($ch, $url);

      $response = curl_exec($ch);
      $json = json_decode($response, true);
      return $json = $this->filterInfo($json['info']);
    }

    private function filterInfo($info){
      //return $info['participants'] = $this->filterParticipants($info['participants']);
      $parts = $this->filterParticipants($info['participants']);

      //return array('info' => array('participants' => $parts));
      //return $info = filterParticipants($info['participants']);
      return array('info' => array('participants' => $parts, 'win' => $info['teams']['0']['win']));
    }

    private function filterParticipants($participants){
      //$returnVal = array('summonerName' => array(), 'champLevel' => array());
      $returnVal = array('0' => [], '1' => [], '2' => [], 
      '3' => [], '4' => [], '5' => [],
      '6' => [], '7' => [],'8' => [], '9' => []);
      $i = 0;
      while($i<10){
        $returnVal[$i]['summonerName'] = $participants[$i]['summonerName'];
        $returnVal[$i]['champLevel'] = $participants[$i]['champLevel'];
        $i++;
      }
      return $returnVal;
    }

    // DINO KECO
    public function getSummonerMatches($summonerName, $region){
      if($region == "na1"){
        $continent = "americas";
      }
      else{
        $continent = "europe";
      }
      $summoner = $this->getSummonerInfo($summonerName, $region);
      $summoner['ranks'] = $this->getSummonerRanks($summoner['id'], $region);
      $j = 0;
      $summoner['matches'] = $this->getSummonerMatchesPrivate($summoner['puuid'], $continent);
      foreach($summoner['matches'] as $i => $match){

        //$summoner['matches'][$i] = getMatchInfo($match['matchid']);
        $summoner['matches'][$i] = $this->getMatchInfo($match, $continent, $j);
        $j++;
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

