<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/services/UserService.class.php';
require_once __DIR__.'/services/RiotService.class.php';
require_once __DIR__.'/dao/BaseDao.class.php';
require_once __DIR__.'/dao/UserDao.class.php';

Flight::register('userService', 'UserService');
Flight::register('riotService', 'RiotService');
/* // OVO JE BESKORISNO... ali nek ostane za sad
Flight::map('error', function(Exception $ex){
    // Handle error
    Flight::json(['message' => $ex->getMessage()], 500);
});
*/

require_once __DIR__.'/routes/UserRoutes.php';
require_once __DIR__.'/routes/RiotRoutes.php';

Flight::start();
