<?php
require_once "../Slim/Slim.php";
Slim\Slim::registerAutoloader ();

$app = new \Slim\Slim (); // slim run-time object
require_once "conf/config.inc.php";

// route middleware for simple API authentication
function authenticate(\Slim\Route $route) {
    $app = \Slim\Slim::getInstance();
	$action = ACTION_VALIDATE_USER;
	$parameters["username"] = $app->request->headers->get("username"); // prepare parameters to be passed to the controller (example: ID)
	$parameters["password"] = $app->request->headers->get("password");

	$mvc = new loadRunMVCComponents ( "UserModel", "UserController", "jsonView", $action, $app, $parameters );
	
    if ($mvc->model->apiResponse === false) {
      $app->halt(401);
    }
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
	return new loadRunMVCComponents ( "UserModel", "UserController", "jsonView", $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/artists(/:id)", function ($artistID = null) use($app) {

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
	return new loadRunMVCComponents ( "ArtistModel", "ArtistController", "jsonView", $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/songs(/:id)", function ($songID = null) use($app) {

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
	return new loadRunMVCComponents ( "SongModel", "SongController", "jsonView", $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

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