<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . "/inc/bootstrap.php";
require PROJECT_ROOT_PATH . "/Controller/Api/AchEditorController.php"; 


if (!isset($_GET["query_type"]))
{
    $baseController = new BaseController();
    $baseController->send404();
    die();
}

$achEditorController = new AchEditorController();

if ($_GET["query_type"] == "game")
{
    $achEditorController->gameList();
}
else if ($_GET["query_type"] == "memory")
{
    $achEditorController->gameMemAddrs();
}
else
{
    $baseController = new BaseController();
    $baseController->sendErrorCode();
    die();
}

?>
