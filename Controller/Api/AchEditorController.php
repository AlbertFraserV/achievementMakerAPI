<?php
class AchEditorController extends BaseController
{

    public function gameList()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
 
        if (strtoupper($requestMethod) == 'GET') {
            try 
            {
                if(isset($_GET['game_key']))
                {
                    $userModel = new AchEditorModel();
                    $arrGame = $userModel->getGameFromGameKey($_GET['game_key']);
                    $responseData = json_encode($arrGame);
                }  

                else if(!isset($_GET['game_key']))
                {
                    $userModel = new AchEditorModel();
                    $arrGame = $userModel->getGameList();
                    $responseData = json_encode($arrGame);
                }

                else
                {
                    $strErrorDesc = 'Invalid Parameter';
                    $strErrorHeader = 'HTTP/1.1 404 Not Found';
                }
            } 
            catch (Error $e) 
            {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else 
        {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
 
        // send output
        if (!$strErrorDesc) 
        {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else 
        {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function gameMemAddrs()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'GET') 
        {
            try
            {
                $achEditorModel = new AchEditorModel();
                $arrGame = $achEditorModel->getGameMemory($_GET['game_key']);
                $responseData = json_encode($arrGame);
            } 
            catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }
        if (!$strErrorDesc) 
        {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else 
        {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}