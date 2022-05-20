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
        $data['password'] = md5($data['password']);
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
        $login = Flight::request()->data->getData();
        $user = Flight::userDao()->get_user_by_email($login['emailLogIn']);
        if (isset($user['id'])){
          if($user['password'] == md5($login['password'])){
            unset($user['password']);
            $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
            Flight::json(['token' => $jwt]);
          }else{
            Flight::json(["message" => "Wrong password"], 404);
          }
        }else{
          Flight::json(["message" => "User doesn't exist"], 404);
        }
    }
      };