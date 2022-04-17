<?php
Flight::route('POST /register', function($requestData){
    Flight::json(Flight::Registration()->register(Flight::request()->data->getData()));
  });
  ?>