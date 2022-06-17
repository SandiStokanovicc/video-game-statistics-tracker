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
/**
* @OA\Post(
*     path="/register",
*     description="Register to the system",
*     tags={"user"},
*     @OA\RequestBody(description="Basic user info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="email", type="string", example="test12@gmail.com"),
*    				@OA\Property(property="username", type="string", example="test12"),
*    				@OA\Property(property="password", type="string", example="123456",	description="Password" ),
*    				)
*     )),
*     @OA\Response(
*         response=200,
*         description="JWT Token on successful response"
*     ),
*     @OA\Response(
*         response=404,
*         description="Wrong Password | User doesn't exist"
*     )
* )
*/

Flight::route('POST /register', function(){
$data = Flight::request()->data->getData();
$data['password'] = md5($data['password']);
$user = Flight::userService()->add($data);
Flight::json($user);}
);
/**
* login user
*/



/**
* @OA\Post(
*     path="/login",
*     description="Login to the system",
*     tags={"user"},
*     @OA\RequestBody(description="Basic user info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="emailLogIn", type="string", example="test@gmail.com"),
*    				@OA\Property(property="passwordLogIn", type="string", example="123456",	description="Password" )
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="JWT Token on successful response"
*     ),
*     @OA\Response(
*         response=404,
*         description="Wrong Password | User doesn't exist"
*     )
* )
*/

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