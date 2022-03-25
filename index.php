<?php
  require 'vendor/autoload.php';

  Flight::route('/', function(){ // goes to "localhost/video-game-statistics-tracker/index.php/"
    //echo 'Video Game Statistics Tracker - Placeholder';
    header('Content-Type: text/html; charset=utf-8');

    ini_set("display_errors", "1"); error_reporting(E_ALL);

    $ch = curl_init(); // initialize cURL_PHP connection

    $apiKey = "RGAPI-35f66bb5-d18d-45e7-bfde-da16b87e495a"; // change every 24h
    $accountName = "Turbo Guardian"; // Condemn for Stun, Turbo Guardian, etc.
    $accountName = str_replace(" ", "%20" , $accountName); // space replaced with "%20" for GET method. Doesn't work otherwise
    $accountName = htmlspecialchars($accountName); // replaces < with &lt, > with &gt, etc. for avoiding XSS attacks

    $url = 'https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $accountName . '?api_key=' . $apiKey;

    /*
    * pass options to the connection
    */

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch); // get results

    curl_close($ch); // close connection

    //print_r($response);
    //echo '<br/>';
    $json = json_decode($response, true); // transform result from JSON (or whatever) into array
    foreach($json as $key=>$value){ // traverse array, print key / val pairs
      print_r($key . " => " . $value . '<br/>');
    }
  });

  Flight::start(); // start framework
 ?>
