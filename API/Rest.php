<?php 


class Rest {
	protected $method = '';
	protected $httpVersion = "HTTP/1.1";
    protected $requestContentType = '';
    public function __construct() {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        $this->requestContentType = $_SERVER['HTTP_ACCEPT'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            }
            else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            }
             else {
                throw new Exception("Unexpected Header");
            }
        }

    }

	public function setHttpHeaders($contentType, $statusCode){
		
		$statusMessage = $this -> getHttpStatusMessage($statusCode);
		
		header($this->httpVersion. " ". $statusCode ." ". $statusMessage);
        if (strpos($contentType,'text/html')!==false){
            $contentType="text";
        }
        header("Content-Type:". $contentType);
	}
	
	public function getHttpStatusMessage($statusCode){
		$httpStatus = array(
			100 => 'Continue',  
			101 => 'Switching Protocols',  
			200 => 'OK',
			201 => 'Created',  
			202 => 'Accepted',  
			203 => 'Non-Authoritative Information',  
			204 => 'No Content',  
			205 => 'Reset Content',  
			206 => 'Partial Content',  
			300 => 'Multiple Choices',  
			301 => 'Moved Permanently',  
			302 => 'Found',  
			303 => 'See Other',  
			304 => 'Not Modified',  
			305 => 'Use Proxy',  
			306 => '(Unused)',  
			307 => 'Temporary Redirect',  
			400 => 'Bad Request',  
			401 => 'Unauthorized',  
			402 => 'Payment Required',  
			403 => 'Forbidden',  
			404 => 'Not Found',  
			405 => 'Method Not Allowed',  
			406 => 'Not Acceptable',  
			407 => 'Proxy Authentication Required',  
			408 => 'Request Timeout',  
			409 => 'Conflict',  
			410 => 'Gone',  
			411 => 'Length Required',  
			412 => 'Precondition Failed',  
			413 => 'Request Entity Too Large',  
			414 => 'Request-URI Too Long',  
			415 => 'Unsupported Media Type',  
			416 => 'Requested Range Not Satisfiable',  
			417 => 'Expectation Failed',  
			500 => 'Internal Server Error',  
			501 => 'Not Implemented',  
			502 => 'Bad Gateway',  
			503 => 'Service Unavailable',  
			504 => 'Gateway Timeout',  
			505 => 'HTTP Version Not Supported');
		return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $httpStatus[500];
	}
}
?>