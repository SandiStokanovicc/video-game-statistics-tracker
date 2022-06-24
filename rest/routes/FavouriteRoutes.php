<?php


Flight::route("POST /addFavourite",  function(){
    $data = Flight::request()->data->getData();
    $favourite = Flight::favouriteService()->add($data);
    Flight::json($favourite);}
 );
 
