<?
//phpinfo();
require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header.php';
if(!Controller_user::authorized()){
	$user = new Controller_user;
	$user->auth_form();
}elseif(Controller_user::is_dispatcher()){
	header("Location: /dispatcher/");
}else{
	header("Location: /driver/");
}
require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/footer.php';
