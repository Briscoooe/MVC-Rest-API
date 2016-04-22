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
		
		// Method 1 - uses native method
		//$xmlResponse = xmlrpc_encode ( $this->model->apiResponse );
		//$this->slimApp->response->write($xmlResponse);
		
		// Method 2 - uses function below
		$xml = new SimpleXMLElement('<root/>');
		$this->xml_encode($xml, $this->model->apiResponse);
		$data = $xml->asXML();
		$this->slimApp->response->write ( $data );
	}
	
	/* http://www.kodingmadesimple.com/2015/11/convert-multidimensional-array-to-xml-file-in-php.html */
	private function xml_encode($obj, $array)
	{
	    foreach ($array as $key => $value)
	    {
	        if(is_numeric($key))
	            $key = 'item' . $key;
	
	        if (is_array($value))
	        {
	            $node = $obj->addChild($key);
	            $this->xml_encode($node, $value);
	        }
	        else
	        {
	            $obj->addChild($key, htmlspecialchars($value));
	        }
	    }
	}
	
}
?>