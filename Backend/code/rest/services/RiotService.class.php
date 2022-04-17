<?php
  class RiotService {

    var $headers = array(
      "Content-Type: text/html; charset=utf-8",
      "User-Agent: StatTrack",
      "Accept-Language: en-US,en;q=0.9",
      "Accept-Charset: application/x-www-form-urlencoded; charset=UTF-8",
      "Origin: https://developer.riotgames.com",
      "X-Riot-Token: RGAPI-03d8dc71-9210-4c9e-a6bf-559890b0a521"
    );
    
    
    public function getSummonerInfo($summonerName, $region){
      $summonerName = str_replace(" ", "%20", $summonerName); // space replaced with "%20" for GET method. Doesn't work otherwise
      $summonerName = htmlspecialchars($summonerName); // replaces < with &lt, > with &gt, etc. for avoiding XSS attacks
      //$region = "eun1";

      $ch = curl_init(); // initialize cURL_PHP connection
      $url = 'https://' . $region .'.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $summonerName;
      /*
      * pass options to the connection
      */

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

      $url = 'https://' . $requestData['continent'] . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $requestData['puuid'] . '/ids?start=0&count=10';

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
  }
  
  private function setCurlOptions($ch, $url){
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  }
}
?>
