<?php


Flight::route("POST /addFavourite",  function(){
    $data = Flight::request()->data->getData();
    $favourite = Flight::favouriteService()->add($data);
    Flight::json($favourite);}
 );




 Flight::route("GET /favourites", function(){
   
  $user = Flight::request()->data->getData();
  if(isset($user)){
  $userFav = Flight::favouriteDao()->getFavouriteById($user);
  Flight::json($userFav);} 
   else {
      Flight::json(["message" => "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA password"], 404);
   }
 });