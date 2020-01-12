<?
require_once $_SERVER["DOCUMENT_ROOT"].'/application/core/model.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/core/view.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/core/controller.php';
session_start();

require_once $_SERVER["DOCUMENT_ROOT"].'/application/lib/lib_abstract.php';

require_once $_SERVER["DOCUMENT_ROOT"].'/application/controllers/controller_user.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/models/model_users.php';

$users_controller = new Controller_user;

if($_GET["logout"]){
	$users_controller->logout();
	header("Location: /");
}

$route = explode("/", $_SERVER["REQUEST_URI"]);

if(!Controller_user::authorized() && $_SERVER['REQUEST_URI'] != '/' && $_SERVER['REQUEST_URI'] != '/index.php'){
	header("Location: /");
}
if(!Controller_user::is_dispatcher() && isset($route[1]) && $route[1] == 'dispatcher'){
	header("Location: /");
}
if(Controller_user::authorized() && ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php')){
	if(Controller_user::is_dispatcher()){
		header("Location: /dispatcher/");
		exit;
	}else{
		header("Location: /driver/");
		exit;
	}
}
?>
