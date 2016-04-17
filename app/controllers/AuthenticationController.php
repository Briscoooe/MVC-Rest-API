<?php
class AuthenticationController {
	private $slimApp;
	private $model;
	private $requestBody;
	public function __construct($model, $action = null, $slimApp, $parameteres = null) {
		$this->model = $model;
		$this->slimApp = $slimApp;
		$this->requestBody = json_decode ( $this->slimApp->request->getBody (), true ); // this must contain the representation of the new user
		
		switch ($action) {
			case ACTION_AUTHENTICATE_USER :
				$username = $parameteres ["username"];
				$password = $parameteres ["password"];
				$this->authenticateUser($username, $password);
				break;	
			case null :
				$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
				$Message = array (
						GENERAL_MESSAGE_LABEL => GENERAL_CLIENT_ERROR 
				);
				$this->model->apiResponse = $Message;
				break;
		}
	}
	private function authenticateUser($username, $password) {
		if($this->model->authenticateUser ($username, $password)){	
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->apiResponse = true;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_UNAUTHORIZED );
			$this->model->apiResponse = false;
		}
	}
}
?>