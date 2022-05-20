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

/**
* List invidiual note
*/
/*
Flight::route('GET /notes/@id', function($id){
  Flight::json(Flight::noteService()->get_by_id($id));
});
*/

/**
* List invidiual note todos
*/
/*
Flight::route('GET /notes/@id/todos', function($id){
  Flight::json(Flight::todoService()->get_todos_by_note_id($id));
});
*/


/**
* update notes
*/
/*
Flight::route('PUT /notes/@id', function($id){
  $data = Flight::request()->data->getData();
  Flight::json(Flight::noteService()->update($id, $data));
});

/**
* delete notes
*/
/*
Flight::route('DELETE /notes/@id', function($id){
  Flight::noteService()->delete($id);
  Flight::json(["message" => "deleted"]);
});
*/
