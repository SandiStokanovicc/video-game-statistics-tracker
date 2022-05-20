<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
// CRUD operations for todos entity

/**
* List all todos
*/
Flight::route('GET /users', function(){
  Flight::json(Flight::userService()->get_all());
});

/**
* register user
*/
Flight::route('POST /authentication/register', ['AuthenticationController', 'register']);

/**
* login user
*/
// Flight::route('POST /authentication/login', ['AuthenticationController', 'login']);

Flight::route('POST /login', function(){
  $login = Flight::request()->data->getData();
  $user = Flight::userDao()->getUserByEmail($login['emailLogIn']);
  if (isset($user['iduser'])){
    if($user['password'] == md5($login['passwordLogIn'])){
      unset($user['password']);
      $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
      Flight::json(['token' => $jwt]);
    }else{
      Flight::json(["message" => "Wrong password"], 404);
    }
  }else{
    Flight::json(["message" => "User doesn't exist"], 404);
  }
});
