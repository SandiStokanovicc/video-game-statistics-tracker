<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'../../vendor/autoload.php';
require_once __DIR__.'/services/UserService.class.php';
require_once __DIR__.'/services/RiotService.class.php';
require_once __DIR__.'/services/RecentSearchesService.class.php';
require_once __DIR__.'/services/FavouriteService.class.php';
require_once __DIR__.'/services/FavouriteMatchService.class.php';
require_once __DIR__.'/services/TipsService.class.php';
require_once __DIR__.'/dao/BaseDao.class.php';
require_once __DIR__.'/dao/UserDao.class.php';
require_once __DIR__.'/dao/RecentSearchesDao.class.php';
require_once __DIR__.'/dao/FavouriteDao.class.php';
require_once __DIR__.'/dao/FavouriteMatchDao.class.php';
require_once __DIR__.'/dao/TipsDao.class.php';

Flight::set('flight.log_errors', true);
Flight::register('userDao', 'UserDao');
Flight::register('favouriteDao', 'FavouriteDao');
Flight::register('favouriteMatchDao', 'FavouriteMatchDao');
Flight::register('summonersDao', 'SummonersDao');
Flight::register('tipsDao', 'TipsDao');
Flight::register('userService', 'UserService');
Flight::register('riotService', 'RiotService');
Flight::register('favouriteService', 'FavouriteService');
Flight::register('favouriteMatchService', 'FavouriteMatchService');
Flight::register('summonersService', 'SummonersService');
Flight::register('recentSearchesService', 'RecentSearchesService');
Flight::register('tipsService', 'TipsService');

// middleware method for login

Flight::route('/*', function(){
    
    //perform JWT decode
    $path = Flight::request()->url;
    if ($path == '/login' || $path == '/register' || $path == '/docs.json' || $path == '/tip' || strpos($path, '/summoners') === 0) return TRUE;
    // || // exclude routes from middleware
    // str_starts_with($path, '/summonersMobileAPI/') || str_starts_with($path, '/summoners/')
    $headers = getallheaders();
    if (@!$headers['Authorization']){
        Flight::json(["message" => "Authorization is missing"], 403);
        return FALSE;
    }else{
        try {
            $decoded = (array)JWT::decode($headers['Authorization'], new Key(Config::JWT_SECRET(), 'HS256'));
            Flight::set('user', $decoded);
            return TRUE;
        } catch (\Exception $e) {
            Flight::json(["message" => "Authorization token is not valid"], 403);
            return FALSE;
        }
    }
  });

  

/* REST API documentation endpoint */
Flight::route('GET /docs.json', function(){
$openapi = \OpenApi\scan('routes');
header('Content-Type: application/json');
echo $openapi->toJson();
});

require_once __DIR__.'/routes/UserRoutes.php';
require_once __DIR__.'/routes/RiotRoutes.php';
require_once __DIR__.'/routes/FavouriteRoutes.php';
require_once __DIR__.'/routes/FavouriteMatchRoutes.php';
require_once __DIR__.'/routes/TipsRoutes.php';

Flight::start();
?>