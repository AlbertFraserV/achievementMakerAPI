<?php
class UserController extends BaseController
{
    /**
     * "/user/list" Endpoint - Get list of users
     */
    public function returnUsers()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
 
        if (strtoupper($requestMethod) == 'GET') {
            try {
                if(!isset($_GET['user_name']) && !isset($_GET['user_key']))
                {
                    $userModel = new UserModel();
                    $intLimit = 10;
                    if(isset($_GET['limit']))
                    {
                        $intLimit = $_GET['limit'];
                    }
                    $arrUsers = $userModel->getUsers($intLimit);
                    $responseData = json_encode($arrUsers);
                }

                else if(isset($_GET['user_name']) && !isset($_GET['user_key']))
                {
                    $userModel = new UserModel();
                    $arrUsers = $userModel->getUsersByName($_GET['user_name']);
                    $responseData = json_encode($arrUsers);
                }

                else if (!isset($_GET['user_name']) && isset($_GET['user_key']))
                {
                    $userModel = new UserModel();
                    $arrUsers = $userModel->getUsersByKey($_GET['user_key']);
                    $responseData = json_encode($arrUsers);
                }

                else if (isset($_GET['user_name']) && isset($_GET['user_key']))
                {
                    $userModel = new UserModel();
                    $arrUsers = $userModel->getUsersByKey($_GET['user_key']);
                    // $arrUsers["message"] = "Both user_key and user_name were passed so the user_name was ignored.";
                    $responseData = json_encode($arrUsers);
                }

                else
                {
                    $strErrorDesc = 'Invalid Parameter';
                    $strErrorHeader = 'HTTP/1.1 404 Not Found';
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
 
        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}
