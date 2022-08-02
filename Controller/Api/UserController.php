<?php
class UserController extends BaseController
{
    /**
     * "/user/list" Endpoint - Get list of users
     */
    public function listAction($user_key = null, $user_name = null)
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
 
        if (strtoupper($requestMethod) == 'GET') {
            try {
                if($user_name == null && $user_key == null)
                {
                    $userModel = new UserModel();
                    $intLimit = 10;
                    if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                        $intLimit = $arrQueryStringParams['limit'];
                    }
    
                    $arrUsers = $userModel->getUsers($intLimit);
                    $responseData = json_encode($arrUsers);
                }

                else if($user_name != null && $user_key == null)
                {
                    $userModel = new UserModel($user_name);
                    $arrUsers = $userModel->getUsersByName($user_name);
                    $responseData = json_encode($arrUsers);
                }

                else if ($user_name == null && $user_key != null)
                {
                    $userModel = new UserModel($user_key);
                    $arrUsers = $userModel->getUsersByKey($user_key);
                    $responseData = json_encode($arrUsers);
                }

                else if ($user_name == null && $user_key != null)
                {
                    $userModel = new UserModel($user_key);
                    $arrUsers = $userModel->getUsersByKey($user_key);
                    $responseData = json_encode(array_unshif(array("message" => "Both user_key and user_name were passed so the user_name was ignored."), $arrUsers));
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