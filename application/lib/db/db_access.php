<?

include_once(dirname(__FILE__)."/db_debug_exception.php");

abstract class db_access extends lib_abstract{

	protected $dbh;

	protected $driver_name;

	protected function __construct($db_type, $db_server, $db_name, $db_user, $db_password){
		if(!$this->driver_name){
			$this->driver_name=$db_type;
		}
		try{

			$this->dbh=new PDO("{$this->driver_name}:host={$db_server};dbname={$db_name}", $db_user, $db_password);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo "Connection failed: ".$e->getMessage();
			exit();
		}
	}

	static public function factory($db_type, $db_server, $db_name, $db_user, $db_password){
		$class_name="db_access_{$db_type}";
		include_once(dirname(__FILE__)."/{$class_name}.php");
		return new $class_name($db_type, $db_server, $db_name, $db_user, $db_password);
	}

	public function sql_select($query, $fields=array(), $special=array()){
		$sth=$this->execute_query($query, $fields, $special);
		$all = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $all;
	}
	

	public function sql_query($query, $fields=array(), $special=array()){
		$sth=$this->execute_query($query, $fields, $special);
		return $sth->rowCount();
	}

	public function insert_record($table, $fields=array(), $special=array()){
		$columns=array();
		$values=array();
		foreach($fields as $key=>$value){
			$columns[]=$key;
			if ($special[$key]=='pure')
				$values[] = $value;
			else
				$values[]=$this->set_parameter_colon($key);
		}
		$columns=join(", ",$columns);
		$values=join(", ",$values);

		$query="INSERT INTO {$table} ({$columns}) VALUES ({$values})";
		$sth=$this->execute_query($query, $fields, $special);
	}

	public function last_insert_id($sequence_name){
		return $this->dbh->lastInsertId($sequence_name);
	}

	public function update_record($table, $fields=array(), $special=array(), $where=array()){
		if ( !is_array( $fields ) || !count( $fields ) ||
			!is_array( $where ) || !count( $where ) ) return;
	
		foreach($fields as $key=>$value){
			if ($special[$key]=='pure')
				$pairs[]="{$key}=$value";
			else 
				$pairs[]="{$key}=".$this->set_parameter_colon($key);
		}
		$pairs=join(", ",$pairs);

		foreach($where as $key=>$value){
			$ands[]="{$key}=:ands_{$key}";
			$fields["ands_".$key]=$value;
		}
		$ands=join(" AND ",$ands);

		$query="UPDATE {$table} SET {$pairs} WHERE {$ands}";
		$sth=$this->execute_query($query, $fields, $special);
		return $sth->rowCount();
	}

	public function delete_record($table, $where=array()){
		if ( !is_array( $where ) || !count( $where ) ) return 0;
		
		foreach($where as $key=>$value){
			$ands[]="{$key}=:ands_{$key}";
			$fields["ands_".$key]=$value;
		}
		$ands=join(" AND ",$ands);

		$query="DELETE FROM {$table} WHERE {$ands}";
		$sth=$this->execute_query($query, $fields, array());
		return $sth->rowCount();
	}

	public function db_quote($content){
		return $this->dbh->quote($content);
	}

	abstract public function concat_clause($fields, $delimiter);

	protected function execute_query($query, $fields, $special){
		$sth=$this->prepare_query($query);
		$params = array();
		foreach($fields as $key=>$value){
			if (is_int($special[$key]))
				$sth->bindValue($this->set_parameter_colon($key), $value, $special[$key]);
			else
				$sth->bindValue($this->set_parameter_colon($key), $value);
			$params[$key] = htmlspecialchars(mb_substr($value, 0, 10, "utf-8"));
			if (mb_strlen($value, "utf-8")>10)
				$params[$key] .= '...';
		}

		try {
			$sth->execute();
		}
		catch (Exception $e) {
			throw new DBDebugException ($e->getMessage(), "\n".$query."\n ".preg_replace('/Array\s*\((.*)\)/s', '\1', print_r($params, 1)));
		}
		return $sth;
	}
	
	
	protected function prepare_query($query) {
		return $this->dbh->prepare($query);
	}

	protected function special_value($value, $type){
		return $value;
	}
	
	public function set_parameter_colon($param) {
		return ':'.$param;
	}	

	protected function get_limit_from_query($query){

		$is_limited=false;
		$offset=0;
		$row_count=0;
		$pure_query=$query;

		if(preg_match("/LIMIT\s*(\d+)(?:\s*,\s*(\d+))?/i", $query, $matches, PREG_OFFSET_CAPTURE)){
			$is_limited=true;
			$pure_query=substr($query, 0, $matches[0][1]);

			if($matches[2][0]){
				$offset=$matches[1][0];
				$row_count=$matches[2][0];

			}else{
				$row_count=$matches[1][0];
			}
		}
		return array("pure_query"=>$pure_query, "is_limited"=>$is_limited, "offset"=>$offset, "row_count"=>$row_count);
	}
}
