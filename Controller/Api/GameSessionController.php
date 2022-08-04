<?php
class GameSessionController extends BaseController
{
    //Returns game, achievement, and user information for the achievement app so users can play games and unlock achievements.
    //Endpoint: /api/gamesession.php
    public function gameSession()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET' && isset($_GET['query_type'])) {
            try {
            
                if ($_GET['query_type'] == 'memory' && isset($_GET['game_key']))
                {
                    $userModel = new GameSessionModel();
                    $arrUsers = $userModel->getMemory($_GET['game_key']);
                    $responseData = json_encode($arrUsers);
                }
                else if ($_GET['query_type'] == 'locked_achs' && isset($_GET['game_key']) && isset($_GET['user_key']))
                {
                    $userModel = new GameSessionModel();
                    $arrUsers = $userModel->getLockedAch($_GET['game_key'], $_GET['user_key']);
                    $responseData = json_encode($arrUsers);
                }

                else if ($_GET['query_type'] == 'ach_prog' && isset($_GET['game_key']) && isset($_GET['user_key']))
                {
                    $userModel = new GameSessionModel();
                    $arrUsers = $userModel->getAchProg($_GET['game_key'], $_GET['user_key']);
                    $responseData = json_encode($arrUsers);
                }

                else 
                {
                    $strErrorDesc = 'Invalid Query';
                    $strErrorHeader = 'HTTP/1.1 404 Not Found';
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }
        else if (strtoupper($requestMethod) == 'POST' && isset($_POST['query_type']))
        {
            try {
            
                if ($_POST['query_type'] == 'unlock_ach' && isset($_POST['user_key']) && isset($_POST['ach_key']))
                {
                    $userModel = new GameSessionModel();
                    $arrUsers = $userModel->unlockAch($_POST['user_key'], $_POST['ach_key']);
                }
                else if ($_POST['query_type'] == 'update_ach_prog' && isset($_POST['unlocked_flag']) && isset($_POST['progress']) && isset($_POST['user_key']) && isset($_POST['ach_key']) && isset($_POST['al_key']))
                {
                    $userModel = new GameSessionModel();
                    $arrUsers = $userModel->updateProg($_POST['unlocked_flag'], $_POST['progress'], $_POST['user_key'], $_POST['ach_key'], $_POST['al_key']);
                }
                else if ($query_type == 'start_game' && $game_key != null && $user_key != null)
                {
                    $userModel = new GameSessionModel();
                    $arrUsers = $userModel->startGame($game_key, $user_key);
                }
                else 
                {
                    $strErrorDesc = 'Invalid Query';
                    $strErrorHeader = 'HTTP/1.1 404 Not Found';
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } 
 
        if (!isset($responseData) && !$strErrorDesc && $_POST['query_type'] > 3)
        {
            $this->sendOutput('POST Successful.', array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
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
