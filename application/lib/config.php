<?
class params{
	public static $params=array();
	public static function init_default_params(){
		self::$params=array(
							"db_type" => array ("value" => "mysql"), // ���� mysql, oracle ��� mssql
							"db_server" => array ("value" => "localhost"), // ������ �� ��� MySQL
							"db_name" => array ("value" => "arm_transport"), // ��� MySQL � MSSQL - �������� ��, ��� Oracle - SID
							"db_user" => array ("value" => "ddobrokhvalov"), // ������������ ��
							"db_password" => array ("value" => 'Bylecnhbz2020'), // ������ ��
						);
	}
}
params::init_default_params();
