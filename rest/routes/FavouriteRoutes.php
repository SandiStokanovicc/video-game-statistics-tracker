<?php


Flight::route("POST /addFavourite",  function(){
    $data = Flight::request()->data->getData();
    $favourite = Flight::favouriteService()->add($data);
    Flight::json($favourite);}
 );


 Flight::route("POST /addFavourite",  function(){
    $data = Flight::request()->data->getData();
    $favourites = Flight::favouriteService()->get_by_id($data);
    Flight::json($favourites);}
 );
