<?php


Flight::route("POST /addFavourite",  function(){
  $data = Flight::request()->data->getData();
  $summonerName = $data['summonerName'];
  $userId = $data['userId'];
  if($userId != Flight::get('user')['iduser']){
    Flight::json(["message" => "Trying to access blocked data"], 403);
    die;
  }
  $currentUser = Flight::favouriteService()->getIdAndSummonerName($userId, $summonerName);
  if(!isset($currentUser['userId'])){
    $favourite = Flight::favouriteService()->add($data);
  }
  else{  
    Flight::json(["message" => "User is already a favourite."], 400);
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


  Flight::route("DELETE /removeFavourite",  function(){
    $data = Flight::request()->data->getData();
    $summonerName = $data['summonerName'];
    $userId = $data['userId'];
    if($userId != Flight::get('user')['iduser']){
      Flight::json(["message" => "Trying to access blocked data"], 403);
      die;
    }
    $currentMatch = Flight::favouriteService()->getIdAndSummonerName($userId, $summonerName);
    if(isset($currentMatch['userId'])){
    $favouriteMatch = Flight::favouriteService()->removeFavouriteSummoner(Flight::get('user'), $summonerName);
    Flight::json(["message" => "Match was removed from favourites"], 200);
    }
    else{
      Flight::json(["message" => "Trying to delete non-existing match..."], 400);
    }
  });