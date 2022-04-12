<?php
//defining database credentials
define("DB_Server", "localhost");
define("DB_Username", "root");
define("DB_Password", "root");
define("DB_Name", "riot");
//connecting to mysql database
try{
    $pdo = new PDO("mysql:host=" . DB_server . ";dbname=" . DB_Name, DB_Username, DB_Password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("Could not connect" . $e->getMessage());
}
?>