<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

//register a user
Flight::route('POST /register', function(){
$data = Flight::request()->data->getData(); //get data from post ajax
$data['password'] = md5($data['password']); //hash the password
$user = Flight::userService()->add($data); //add user to db
Flight::json($user);}
);


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

//user login
Flight::route('POST /login', function(){
  $login = Flight::request()->data->getData(); //get data from post ajax
  $user = Flight::userDao()->getUserByEmail($login['emailLogIn']); 
  if (isset($user['iduser'])){ //check if user exists in db
    if($user['password'] == md5($login['passwordLogIn'])){ //check password validity
      unset($user['password']);
      $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256'); //if all ios good, create jwt token and return it to the ajax call
      Flight::json(['token' => $jwt]);
    }else{
      Flight::json(["message" => "Wrong password"], 404);
    }
  }else{
    Flight::json(["message" => "User doesn't exist"], 404);
  }
});