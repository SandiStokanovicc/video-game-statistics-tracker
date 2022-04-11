<?php
  ini_set("display_errors", "1");
  error_reporting(E_ALL);

  require 'vendor/autoload.php';

  Flight::route('/', function(){ // goes to "localhost/video-game-statistics-tracker/index.php/"
    //echo 'Video Game Statistics Tracker - Placeholder';
    header('Content-Type: text/html; charset=utf-8');

    $headers = array(
      "User-Agent: StatTrack",
      "Accept-Language: en-US,en;q=0.9",
      "Accept-Charset: application/x-www-form-urlencoded; charset=UTF-8",
      "Origin: https://developer.riotgames.com",
      "X-Riot-Token: RGAPI-31bc02d1-bfde-4e6f-b349-1c36fb3ea9ab"
    );

    $ch = curl_init(); // initialize cURL_PHP connection

    $apiKey = "RGAPI-31bc02d1-bfde-4e6f-b349-1c36fb3ea9ab"; // change every 24h
    $accountName = "Condemn for Stun"; // Condemn for Stun, Turbo Guardian, etc.
    $accountName = str_replace(" ", "%20" , $accountName); // space replaced with "%20" for GET method. Doesn't work otherwise
    $accountName = htmlspecialchars($accountName); // replaces < with &lt, > with &gt, etc. for avoiding XSS attacks
    $region = "eun1";

    $url = 'https://' . $region .'.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $accountName . '?api_key=' . $apiKey;
    /*
    * pass options to the connection
    */

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch); // get results

    curl_close($ch); // close connection

    $newArray = array();
    $json = json_decode($response, true); // transform result from JSON (or whatever) into array
    foreach($json as $key=>$value){ // traverse array, print key / val pairs
      if($key == "id" || $key ==  "accountId" || $key == "puuid" || $key == "name" || $key == "profileIconId") $newArray += [$key => $value];
    }
    foreach($newArray as $key=>$value) print_r($key . " => " . $value . '<br/>');
  });

  Flight::route('/getMatches', function(){  //proradilo je, hvala Bogu!
    //da bi se testirala ova ruta, ide se na localhost/video-game-statistics-tracker/getMatches (trenutno su podaci hard-coded, trebat ce se kasnije preko frontenda to promijeniti)
    //$puuid je path parameter (mora biti unutar url-a), dok su $start i $count query params (optional)
    $puuid = "bFIevMKyxaPWODOXdHmEz8G5fwQ_C6QmHl0R3jNpuc5HgCRDOQ4oPZ-miFQK7GSj1BoDq-obtFt76Q";
    $start = 0;
    $count = 100;

    $headers = array(
      "User-Agent: StatTrack",
      "Accept-Language: en-US,en;q=0.9",
      "Accept-Charset: application/x-www-form-urlencoded; charset=UTF-8",
      "Origin: https://developer.riotgames.com",
      "X-Riot-Token: RGAPI-31bc02d1-bfde-4e6f-b349-1c36fb3ea9ab"
    );

    $ch = curl_init();

    $continent = "europe";
    $url2 = 'https://' . $continent . '.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $puuid . '/ids?start=' . $start . '&count=' . $count;

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $json = json_decode($response);
    foreach($json as $key=>$value) print_r($value . '<br/>');
  });
  Flight::start(); // start framework
 ?>
