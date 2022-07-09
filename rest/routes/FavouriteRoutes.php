<?php


Flight::route("POST /addFavourite",  function(){
  $data = Flight::request()->data->getData();
  $summonerName = $data['summonerName'];
  $userId = $data['userId'];
  $currentUser = Flight::favouriteService()->getIdAndSummonerName($userId, $summonerName);
  if(!isset($currentUser['userId'])){
  $favourite = Flight::favouriteService()->add($data);
}
}
 );





 Flight::route("POST /favourites", function(){
   
   $user = Flight::request()->data->getData();
   $userId = $user['iduser'];
   $favourites = Flight::favouriteService()->getFavouriteById($userId);
   Flight::json($favourites);
  });