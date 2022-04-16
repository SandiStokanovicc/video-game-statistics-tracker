<?php
  class RiotService {

    var $headers = array(
      "Content-Type: text/html; charset=utf-8",
      "User-Agent: StatTrack",
      "Accept-Language: en-US,en;q=0.9",
      "Accept-Charset: application/x-www-form-urlencoded; charset=UTF-8",
      "Origin: https://developer.riotgames.com",
      "X-Riot-Token: RGAPI-d4749e55-7326-49ad-9273-3b1664b72309"
    );
    
    
    public function getSummonerInfo($summonerName, $region){
      //echo $summonerName;
      //$summonerName = "Condemn for Stun"; // Condemn for Stun, Turbo Guardian, etc.
      $summonerName = str_replace(" ", "%20", $summonerName); // space replaced with "%20" for GET method. Doesn't work otherwise
      $summonerName = htmlspecialchars($summonerName); // replaces < with &lt, > with &gt, etc. for avoiding XSS attacks
      //$region = "eun1";

      $ch = curl_init(); // initialize cURL_PHP connection
      $url = 'https://' . $region .'.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $summonerName;
      /*
      * pass options to the connection
      */
      curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      $response = curl_exec($ch); // get results

      curl_close($ch); // close connection

      $json = json_decode($response, true); // transform result from JSON (or whatever) into array
      //var_dump($json);
      return $json;
      
      //print_r("id => " . $json["id"] . "<br/>accountId => " . $json["accountId"] . "<br/>puuid => " . $json["puuid"] . "<br/>name => " . $json["name"] . "<br/>profileIconId => " . $json["profileIconId"]);
      //Flight::json($json);
      //return $json;
      //Flight::response($json);

    }


    public function getMatchIDs($requestData){
    //da bi se testirala ova ruta, ide se na localhost/video-game-statistics-tracker/getMatches (trenutno su podaci hard-coded, trebat ce se kasnije preko frontenda to promijeniti)
    //$puuid je path parameter (mora biti unutar url-a), dok su $start i $count query params (optional)

    //$puuid = $_REQUEST["puuid"];
    //$puuid = Flight::request()->data->puuid;
    //if(isset)
    //$puuid = "bFIevMKyxaPWODOXdHmEz8G5fwQ_C6QmHl0R3jNpuc5HgCRDOQ4oPZ-miFQK7GSj1BoDq-obtFt76Q";
    $puuid = $requestData['puuid'];
    $continent = $requestData['continent'];
    $start = 0;
    $count = 10;



    $ch = curl_init();

    //$continent = "europe";
    $url2 = 'https://' . $requestData['continent'] . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $requestData['puuid'] . '/ids?start=' . $start . '&count=' . $count;

    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($ch, CURLOPT_URL, $url2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $json = json_decode($response, true);
    return $json;
    //print_r($json);
    //foreach($json as $key=>$value) print_r($value . '<br/>');
    }
  }
?>
