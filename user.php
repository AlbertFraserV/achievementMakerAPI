<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . "/inc/bootstrap.php";
 
$uri = $_SERVER['REQUEST_URI'];
$uri = explode( '?', $uri );

$output = [];
if (isset($uri[1]))
{   
    $uri = explode('&', $uri[1]);
    foreach ($uri as $input) 
    {
        $params = explode("=", $input);
        $output[$params[0]] = $params[1];
    }
}

// print_r($output);
// exit;
// $user_key = '51';
// $param = 'user_key';

// if (isset($uri[1]))
// {
//     header("HTTP/1.1 404 Not Found");
//     exit();
// }
 
require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
 
if (isset($output['user_name']) && isset($output['user_key']))
{
    $objFeedController = new UserController(
        $user_key= rawurldecode($output['user_key']), 
        $user_name = rawurldecode($output['user_name'])
    );
    $strMethodName = 'list' . 'Action';
    $objFeedController->{$strMethodName}(
        $user_name = rawurldecode($output['user_key']),
        $user_name = rawurldecode($output['user_name'])
    );
}
else if (isset($output['user_key']))
{
    $objFeedController = new UserController($user_key = rawurldecode($output['user_key']));
    $strMethodName = 'list' . 'Action';
    $objFeedController->{$strMethodName}($user_key = rawurldecode($output['user_key']));
}
else if (isset($output['user_name']))
{   
    $objFeedController = new UserController(null, rawurldecode($output['user_name']));
    $strMethodName = 'list' . 'Action';
    $objFeedController->{$strMethodName}(null, rawurldecode($output['user_name']));
}
else if (count($output) == 0)
{
    $objFeedController = new UserController();
    $strMethodName = 'list' . 'Action';
    $objFeedController->{$strMethodName}();
}
else
{
    echo json_encode(array('Response' => 'Input Handling Error.'));
    exit();
}
?>