<?php
class xmlView {
	private $model, $controller, $slimApp;
	public function __construct($controller, $model, $slimApp) {
		$this->controller = $controller;
		$this->model = $model;
		$this->slimApp = $slimApp;
	}
	public function output() {
		// prepare xml response
		
		$root = null;
		$xml = new SimpleXMLElement($root ? '<' . $root . '/>' : '<results/>');
		array_walk_recursive($this->model->apiResponse, function($value, $key)use($xml){
			$xml->addChild($key, $value);
		});
		
		$dom = new DOMDocument("1.0");
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		$data = $dom->saveXML();;
		
		$this->slimApp->response->write ( $data );
	}
}
?>