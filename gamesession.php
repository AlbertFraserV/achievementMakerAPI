<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . "/inc/bootstrap.php";
require PROJECT_ROOT_PATH . "/Controller/Api/GameSessionController.php"; 

$uri = $_SERVER['REQUEST_URI'];
$uri = explode( '?', $uri );
$requestMethod = $_SERVER["REQUEST_METHOD"];

if(isset($_GET['query_type']) || isset($_POST['query_type']))
{
    $gameSessionController = new GameSessionController();
    $gameSessionController->gameSession();
}
else
{
    $baseController = new BaseController();
    $baseController->sendErrorCode();
    die();
}
?>