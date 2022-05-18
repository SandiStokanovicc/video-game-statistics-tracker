<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthenticationController {
    /**
     * Register a new user.
     */
    public static function register() {
        // get data from request
        $data = Flight::request()->data->getData();
      
        // validate data maybe?
      
        // Create a user
        $user = Flight::userService()->add($data);
      
        // return user as JSON
        Flight::json($user);
    }

    /**
     * Login user.
     */
    public static function login() {
        // get data from request
        $data = Flight::request()->data->getData();
        $user = Flight::userDao()->getUserByEmail($data['username']);
        if(isset($user['id'])){

            if($user['password'] == $data['password']){
              unset($user['password']);
              $jwt = JWT::encode($data, Config::JWT_SECRET(), 'HS256');
              Flight::json(['token' => $jwt]);
            }else{
              Flight::json(["message"=>"Password is incorrect"], 404);
            }
          }else{
            Flight::json(["message"=>"User with that username doesn't exist"], 404);
          }
        // return user as JSON
        Flight::json($user);
    
        };
      };