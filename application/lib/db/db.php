<?
include_once(dirname(__FILE__)."/db_access.php");

class db extends lib_abstract{

	private static $db_access;

	private static function singleton(){
		if(!is_object(self::$db_access)){
			self::$db_access=db_access::factory(params::$params['db_type']['value'], 
												params::$params['db_server']['value'], 
												params::$params['db_name']['value'], 
												params::$params['db_user']['value'], 
												params::$params['db_password']['value']);
		}
		return self::$db_access;
	}
	
	public static function get_db_object () {
		return self::singleton();
	}

	public static function sql_select($query, $fields=array(), $special=array()){
		return self::singleton()->sql_select($query, $fields, $special);
	}

	public static function sql_query($query, $fields=array(), $special=array()){
		return self::singleton()->sql_query($query, $fields, $special);
	}

	public static function insert_record($table, $fields=array(), $special=array()){
		return self::singleton()->insert_record($table, $fields, $special);
	}

	public static function last_insert_id($sequence_name){
		return self::singleton()->last_insert_id($sequence_name);
	}

	public static function update_record($table, $fields=array(), $special=array(), $where=array()){
		return self::singleton()->update_record($table, $fields, $special, $where);
	}

	public static function delete_record($table, $where=array()){
		return self::singleton()->delete_record($table, $where);
	}

	public static function db_quote($content){
		return self::singleton()->db_quote($content);
	}

	public static function concat_clause($fields, $delimiter){
		return self::singleton()->concat_clause($fields, $delimiter);
	}
	
	public static function replace_field ($sql_result, $search_field, $replace_field)  {
		if (is_array($sql_result) && sizeof($sql_result)) 
			foreach ($sql_result as &$row) 
				if (is_array($search_field) && is_array($replace_field) && (sizeof($search_field)==sizeof($replace_field)))
					for ($i=0, $n=sizeof($search_field); $i<$n; $i++) {
						$row[$search_field[$i]] = $row[$replace_field[$i]];
						unset($row[$replace_field[$i]]);
					}
				elseif (is_scalar($search_field) && is_scalar($replace_field)) {
					$row[$search_field] = $row[$replace_field];
					unset($row[$replace_field]);
				}
		
		return $sql_result;
	}
}