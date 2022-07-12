<?php


Flight::route("POST /addFavourite",  function(){
  $data = Flight::request()->data->getData();
  $summonerName = $data['summonerName'];
  $userId = $data['userId'];
  $currentUser = Flight::favouriteService()->getIdAndSummonerName($userId, $summonerName);
  if(!isset($currentUser['userId'])){
    $favourite = Flight::favouriteService()->add($data);
  }
  else{  
    Flight::json(["message" => "User is already a favourite."], 500);
  }
});





 Flight::route("POST /favourites", function(){
   
   $user = Flight::request()->data->getData();
   $userId = $user['iduser'];
   $favourites = Flight::favouriteService()->getFavouriteById($userId);
   Flight::json($favourites);
  });


  Flight::route('GET /favList/@summonerName/@region', function($summonerName, $region){ 
    Flight::json(Flight::riotService()->getSummonerInfo($summonerName, $region));
  });