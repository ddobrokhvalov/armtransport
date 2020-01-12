<?

class db_access_mysql extends db_access{

	function __construct($db_type, $db_server, $db_name, $db_user, $db_password){
		parent::__construct($db_type, $db_server, $db_name, $db_user, $db_password);
		$charset="utf8";
		
		$sth=$this->dbh->prepare("SET CHARACTER SET {$charset}");
		$sth->execute();
		$sth=$this->dbh->prepare("SET NAMES '{$charset}'");
		$sth->execute();
	}

	public function concat_clause($fields, $delimiter){
		if(count($fields)>1){
			foreach($fields as $key=>$field){
				if($key==0){
					$full_fields[]="IFNULL(".$field.", '')";
				}else{
					$full_fields[]="IF({$field} IS NULL, '', CONCAT('{$delimiter}', {$field}))";
				}
			}
			return "CONCAT(".join(', ', $full_fields).")";
		}else{
			return $fields[0];
		}
	}
}