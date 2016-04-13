<?php
class jsonView {
	private $model, $controller, $slimApp;
	public function __construct($controller, $model, $slimApp) {
		$this->controller = $controller;
		$this->model = $model;
		$this->slimApp = $slimApp;
	}
	public function output() {
		// prepare json response
		$jsonResponse = json_encode ( $this->model->apiResponse );
		$this->slimApp->response->write ( $jsonResponse );
	}
}
?>