<?
//phpinfo();
require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header.php';
if(!Controller_user::authorized()){
	$user = new Controller_user;
	$user->auth_form();
}elseif(Controller_user::is_dispatcher()){
	//ob_end_clean();
	header("Location: /dispatcher/");
}else{
	//ob_end_clean();
	header("Location: /driver/");
}
require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/footer.php';
