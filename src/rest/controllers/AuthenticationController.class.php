<?php

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
      
        // validate data maybe? - username required, password required
      
        // Get user from database - if not ok return error

        // Check credentials - if not ok return error
        
        // Start sessioon
      
        // return user as JSON
        Flight::json($user);
    }
}