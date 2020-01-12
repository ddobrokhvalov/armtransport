<?
class params{
	public static $params=array();
	public static function init_default_params(){
		self::$params=array(
							"db_type" => array ("value" => "mysql"), // СУБД mysql, oracle или mssql
							"db_server" => array ("value" => "localhost"), // Сервер БД для MySQL
							"db_name" => array ("value" => "arm_transport"), // Для MySQL и MSSQL - название БД, для Oracle - SID
							"db_user" => array ("value" => "ddobrokhvalov"), // Пользователь БД
							"db_password" => array ("value" => 'Bylecnhbz2020'), // Пароль БД
						);
	}
}
params::init_default_params();
