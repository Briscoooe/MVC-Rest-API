<?php

/*
 * TODO:
 * Ask luca
 * How to validate if ID exists in table -  try catch allowed?
 * How to not have three separate search functions
 */
require_once "../Slim/Slim.php";
Slim\Slim::registerAutoloader ();

$app = new \Slim\Slim (); // slim run-time object
require_once "conf/config.inc.php";

// route middleware for simple API authentication
function authenticate(\Slim\Route $route) {
    $app = \Slim\Slim::getInstance();
	$action = ACTION_AUTHENTICATE_USER;
	$parameters["username"] = $app->request->headers->get("username");
	$parameters["password"] = $app->request->headers->get("password");

	$mvc = new loadRunMVCComponents ( "UserModel", "UserController", "jsonView", $action, $app, $parameters );
    if ($mvc->model->apiResponse === false) {
		$app->halt(401);
    }
}

function checkType($app) {
	$viewType = $app->request->headers->get("Content-Type");
	
	if($viewType == RESPONSE_FORMAT_XML)
		$viewType = "xmlView";
	else
		$viewType = "jsonView";
	
	return $viewType;
}

$app->map ( "/users(/:id)", "authenticate", function ($userID = null) use($app) {
	
	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["id"] = $userID; // prepare parameters to be passed to the controller (example: ID)
	
	if (($userID == null) or is_numeric ( $userID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($userID != null)
					$action = ACTION_GET_USER;
				else
					$action = ACTION_GET_USERS;
				break;
			case "POST" :
				$action = ACTION_CREATE_USER;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_USER;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_USER;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "UserModel", "UserController", $viewType, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/users/search(/:string)", "authenticate", function ($searchString = null) use($app) {

	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["searchString"] = $searchString; // prepare parameters to be passed to the controller (example: ID)

	if (($searchString == null) or is_string( $searchString )) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_USERS;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "UserModel", "UserController", $viewType, $action, $app, $parameters );
} )->via ( "GET");

$app->map ( "/artists(/:id)", "authenticate", function ($artistID = null) use($app) {

	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["id"] = $artistID; // prepare parameters to be passed to the controller (example: ID)

	if (($artistID == null) or is_numeric ( $artistID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($artistID != null)
					$action = ACTION_GET_ARTIST;
				else
					$action = ACTION_GET_ARTISTS;
				break;
			case "POST" :
				$action = ACTION_CREATE_ARTIST;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_ARTIST;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_ARTIST;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "ArtistModel", "ArtistController", $viewType, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/artists/search(/:string)", "authenticate", function ($searchString = null) use($app) {

	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["searchString"] = $searchString; // prepare parameters to be passed to the controller (example: ID)

	if (($searchString == null) or is_string( $searchString )) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_ARTISTS;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "ArtistModel", "ArtistController", $viewType, $action, $app, $parameters );
} )->via ( "GET");

$app->map ( "/songs(/:id)", "authenticate", function ($songID = null) use($app) {

	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["id"] = $songID; // prepare parameters to be passed to the controller (example: ID)

	if (($songID == null) or is_numeric ( $songID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($songID != null)
					$action = ACTION_GET_SONG;
				else
					$action = ACTION_GET_SONGS;
				break;
			case "POST" :
				$action = ACTION_CREATE_SONG;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_SONG;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_SONG;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "SongModel", "SongController", $viewType, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/songs/search(/:string)", "authenticate", function ($searchString = null) use($app) {

	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["searchString"] = $searchString; // prepare parameters to be passed to the controller (example: ID)

	if (($searchString == null) or is_string( $searchString )) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_SONGS;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "SongModel", "SongController", $viewType, $action, $app, $parameters );
} )->via ( "GET");

$app->run ();
class loadRunMVCComponents {
	public $model, $controller, $view;
	public function __construct($modelName, $controllerName, $viewName, $action, $app, $parameters = null) {
		include_once "models/" . $modelName . ".php";
		include_once "controllers/" . $controllerName . ".php";
		include_once "views/" . $viewName . ".php";
		
		$this->model = new $modelName (); // common model
		$this->controller = new $controllerName ( $this->model, $action, $app, $parameters );
		$this->view = new $viewName ( $this->controller, $this->model, $app, $app->headers ); // common view
		$this->view->output (); // this returns the response to the requesting client
	}
}

?>