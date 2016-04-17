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
		//$xmlResponse = xmlrpc_encode ( $this->model->apiResponse );
		// $this->slimApp->response->write($this->xml_encode($this->model->apiResponse));
		
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
	
	/* References https://www.darklaunch.com/2009/05/23/php-xml-encode-using-domdocument-convert-array-to-xml-json-encode */
	/*
	private function xml_encode($mixed, $domElement=null, $DOMDocument=null) {
	    if (is_null($DOMDocument)) {
	        $DOMDocument =new DOMDocument;
	        $DOMDocument->formatOutput = true;
	        $this->xml_encode($mixed, $DOMDocument, $DOMDocument);
	        echo $DOMDocument->saveXML();
	    }
	    else {
	        if (is_array($mixed)) {
	            foreach ($mixed as $index => $mixedElement) {
	                if (is_int($index)) {
	                    if ($index === 0) {
	                        $node = $domElement;
	                    }
	                    else {
	                        $node = $DOMDocument->createElement($domElement->tagName);
	                        $domElement->parentNode->appendChild($node);
	                    }
	                }
	                else {
	                    $plural = $DOMDocument->createElement($index);
	                    $domElement->appendChild($plural);
	                    $node = $plural;
	                    if (!(rtrim($index, 's') === $index)) {
	                        $singular = $DOMDocument->createElement(rtrim($index, 's'));
	                        $plural->appendChild($singular);
	                        $node = $singular;
	                    }
	                }
	 
	                $this->xml_encode($mixedElement, $node, $DOMDocument);
	            }
	        }
	        else {
	            $domElement->appendChild($DOMDocument->createTextNode($mixed));
	        }
	    }
	}*/
}
?>