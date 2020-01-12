<?
class Model_Users extends Model{
	
	public function get_dispatcher_by_login($user_login){
		$user = db::sql_select("select * from dispatchers where login = :login", array("login"=>$user_login));
		return $user;
	}
	
	public function get_driver_by_login($user_login){
		$user = db::sql_select("select * from drivers where login = :login", array("login"=>$user_login));
		return $user;
	}

}
