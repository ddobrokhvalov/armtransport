<?
class db_access_mssql extends db_access{

	function __construct($db_type, $db_server, $db_name, $db_user, $db_password){
		if(!$this->driver_name){
			$this->driver_name=$db_type;
		}
		try{
			$this->dbh=new PDO("sqlsrv:server={$db_server};database={$db_name}", $db_user, $db_password);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo "Connection failed: ".$e->getMessage();
			exit();
		}
	}

	public function concat_clause($fields, $delimiter){
		if(count($fields)>1){
			foreach($fields as $key=>$field){
				if($key==0){
					$full_fields[]="NULLIF(".$field.", '')";
				}else{
					$full_fields[]="CASE {$field} WHEN NULL THEN '' ELSE '{$delimiter}' + {$field} END";
				}
			}
			return join(' + ', $full_fields);
		}else{
			return $fields[0];
		}
	}
	public function last_insert_id($sequence_name){
		$res = parent::sql_select('SELECT @@IDENTITY AS CURRVAL');
		return $res[0]['CURRVAL'];
	}

	public function db_quote($str)
	{
		return str_replace("\0", '', parent::db_quote($str));
	}
	function getErrors()
	{
		return $this->dbh->errorInfo();
	}

	public function sql_query($query, $fields=array(), $special=array()){

		$sth=$this->execute_query($query, $fields, $special);
		return $sth->rowCount();
	}

	public function sql_select($query, $fields=array(), $special=array()){
	if (preg_match('/LIMIT\s+((\d+)\s*,)?\s*(\d+)/i', $query, $matches)) {
		$from = (int)$matches[2];
		$count = (int)$matches[3];
		if ($count) {
			$orderby_expr = '/(ORDER BY\s+(.+))/i';
			if (preg_match($orderby_expr, $query, $m2)) {

				$query = preg_replace($orderby_expr, '', $query);
				$query = preg_replace('/^(\s*SELECT(\s+DISTINCT)?\s+)/i', 'SELECT\2 * FROM (SELECT ROW_NUMBER() OVER ('.$m2[1].') AS _RBC_ROW_NUMBER_,', $query).
					') AS RES WHERE RES._RBC_ROW_NUMBER_ BETWEEN '.($from + 1).' AND '.($from + $count).' ORDER BY RES._RBC_ROW_NUMBER_';
			}
			else {
				$query = preg_replace('/^\s*(SELECT(\s+DISTINCT)?\s+)/i', 'SELECT\2 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY (SELECT 1)) AS _RBC_ROW_NUMBER_,', $query).
					') AS RES WHERE RES._RBC_ROW_NUMBER_ BETWEEN '.($from + 1).' AND '.($from + $count).' ORDER BY RES._RBC_ROW_NUMBER_';
			}
			$query = preg_replace('/LIMIT\s+((\d+)\s*,)?\s*(\d+)/i', '', $query);
			$removeLimiter = 1;
		}
	}

		$sth=$this->execute_query($query, $fields, $special);
		$all = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $all;
	}

	public function insert_record($table, $fields=array(), $special=array())
	{
		@$tableInfo = metadata::$objects[$table]['fields'];
		if(!empty($tableInfo) && is_array($tableInfo)){
			$isIdentity = false;
			foreach($tableInfo as $fieldName => $fieldInfo){
				if($fieldInfo['pk'] && $fieldInfo['auto_increment'] && array_key_exists($fieldName, $fields)){
					$isIdentity = true;;
					break;
				};
			};
			
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

			$query = '';
			if($isIdentity){
				$query .= 'SET IDENTITY_INSERT ' . $table . ' ON';
			};

			$query .= PHP_EOL . "INSERT INTO {$table} ({$columns}) VALUES ({$values})";

			if($isIdentity){
				$query .= PHP_EOL . 'SET IDENTITY_INSERT ' . $table . ' OFF';
			};

			$sth=$this->execute_query($query, $fields, $special);

		} else {
			throw new Exception('Out of table "'.$table.'" description.');
		};
	}

}