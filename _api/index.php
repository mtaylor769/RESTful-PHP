<?php
setlocale(LC_ALL, en_US);
date_default_timezone_set("America/Los_Angeles");
session_start();

include_once("./db_flib.php");


spl_autoload_register('apiAutoload');
function apiAutoload($classname) {
    if (preg_match('/[a-zA-Z]+Controller$/', $classname)) {
        include __DIR__ . '/controllers/' . $classname . '.php';
        return true;
    } elseif (preg_match('/[a-zA-Z]+Model$/', $classname)) {
        include __DIR__ . '/models/' . $classname . '.php';
        return true;
    } elseif (preg_match('/[a-zA-Z]+View$/', $classname)) {
        include __DIR__ . '/views/' . $classname . '.php';
        return true;
    }
}
//echo "completed apiAutoload<br>";
$req = new Request();

//print_r($req->url_elements);

// route the request to the right place
$controller_name = ucfirst($req->url_elements[2]) . 'Controller';
if (class_exists($controller_name)) {
    $controller = new $controller_name();
    $action_name = strtolower($req->verb) . 'Action';
    $result = $controller->$action_name($req);
    //print_r($result);
    
	// return the response in the correct format
	$view_name = ucfirst($req->format) . 'View';
	if(class_exists($view_name)) {
		$view = new $view_name();
		$view->render($result);
	}
	
}

/*
$res = elastic_rest("GET","/coordinates/coordinate/_search","q=account_id:my_account_id");
print_r($res['hits']['hits'][0]['_source']);
$lat = $res['hits']['hits'][0]['_source']['point']['lat'];
$lon = $res['hits']['hits'][0]['_source']['point']['lon'];

echo "<br><br>------<br>";
$user = login('mtaylor','pa55w0rd');
*/
?>
