<?php
class BaseController
{
    // __call magic method.
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }
 
    //Send API output.
    protected function sendOutput($data, $httpHeaders=array())
    {
        header_remove('Set-Cookie');
 
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
 
        echo $data;
        exit;
    }

    //Sends 404 error. This can be expanded into more error codes later.
    public function sendErrorCode()
    {
        $this->sendOutput(
            json_encode(array("response" => "Invalid Query.")),
            array('Content-Type: application/json', 'HTTP/1.1 404 Not Found')
        );
    }
}
