<?php

class Config {

  public static function DB_HOST(){
    return Config::get_env("DB_HOST", "localhost");
  }
  public static function DB_USERNAME(){
    return Config::get_env("DB_USERNAME", "riot");
  }
  public static function DB_PASSWORD(){
    return Config::get_env("DB_PASSWORD", "riot");
  }
  public static function DB_SCHEME(){
    return Config::get_env("DB_SCHEME", "riot");
  }
  public static function DB_PORT(){
    return Config::get_env("DB_PORT", "3307");
  }
  public static function JWT_SECRET(){
    return Config::get_env("JWT_SECRET", "ezcb9s8UcF");
  }

  // USED FOR FETCHING VARIOUS HIDDEN DATA FROM ENVIRONMENT VARIABLES FOR SECURITY REASONS + USES DEFAULT IF NONE ARE FOUND
  public static function get_env($name, $default){
   return isset($_ENV[$name]) && trim($_ENV[$name]) != '' ? $_ENV[$name] : $default;
  }
  
}
