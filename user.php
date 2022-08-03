<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . "/inc/bootstrap.php";
 
$uri = $_SERVER['REQUEST_URI'];
 
require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
 
$userController = new UserController();
$userController->returnUsers();
?>
