<?

include_once(dirname(__FILE__)."/db_oracle_oci_statement_to_pdo.php"); 
 
class db_access_oracle extends db_access{

	protected $lob_cache;

	 
	function __construct($db_type, $db_server, $db_name, $db_user, $db_password){
		$this->dbh = oci_pconnect($db_user, $db_password, $db_name, 'UTF8');
	}

	
	public function sql_select($query, $fields=array(), $special=array()) {
		$parsed_query=$this->get_limit_from_query($query);

		if($parsed_query["is_limited"]){

			if($parsed_query["offset"]){
				$query="
					SELECT TABLE_LIMIT.* FROM(
						SELECT TABLE_LIMIT.*, ROWNUM AS ROWNUM_LIMIT FROM(
							{$parsed_query["pure_query"]}
					    )TABLE_LIMIT WHERE ROWNUM<=:lim_row_count_plus_offset
					)TABLE_LIMIT WHERE ROWNUM_LIMIT>:lim_offset
				";
				$fields+=array("lim_row_count_plus_offset"=>$parsed_query["row_count"]+$parsed_query["offset"], "lim_offset"=>$parsed_query["offset"]);

			}else{
				$query="
					SELECT TABLE_LIMIT.*, ROWNUM FROM(
						{$parsed_query["pure_query"]}
					)TABLE_LIMIT WHERE ROWNUM<=:lim_row_count
				";
				$fields+=array("lim_row_count"=>$parsed_query["row_count"]);
			}
		}
		
		
		return parent::sql_select($query, $fields, $special);
	}
	
	
	protected function prepare_query($query) {
		$sth = @oci_parse($this->dbh, $query);
		if (!$sth) {
			$e = oci_error($this->dbh);
			throw new DBDebugException($e['message'], "\n{$e['sqltext']}\n");
		}

		return new db_oracle_oci_statement_to_pdo($this->dbh, $sth);
	}
	
	
	public function db_quote($content){
		return "'".preg_replace("/'/", "''", $content)."'";
	}
	
	


	public function concat_clause($fields, $delimiter){
		if(count($fields)>1){
			foreach($fields as $key=>$field){
				if($key==0){
					$full_fields[]="NVL(".$field.", '')";
				}else{
					$full_fields[]="NVL2({$field}, '{$delimiter}' || {$field}, '')";
				}
			}
			return join(' || ', $full_fields);
		}else{
			return $fields[0];
		}
	}

	 
	protected function get_lobs($table){

		if(!isset($this->lob_cache[$table])){

			$lobs=parent::sql_select("
				SELECT USER_TAB_COLUMNS.COLUMN_NAME, USER_TAB_COLUMNS.DATA_TYPE FROM USER_TAB_COLUMNS
					WHERE USER_TAB_COLUMNS.TABLE_NAME=:table1
						AND (USER_TAB_COLUMNS.DATA_TYPE IN ('BLOB', 'CLOB', 'NCLOB', 'BFILE', 'LONG_RAW'))
			", array("table1"=>$table));

			foreach($lobs as $lob){
				$done_lobs[$lob["COLUMN_NAME"]]=$lob["DATA_TYPE"];
			}
			$this->lob_cache[$table]=(is_array($done_lobs) ? $done_lobs : array());
		}
		return $this->lob_cache[$table];
	}
	
	
	 
	public function insert_record($table, $fields=array(), $special=array()){
		if (!is_array($special)) $special=array();

		$lob_fields = $this->get_lobs($table);

		$columns = array();
		$values = array();
		$lobs = array();
		
		foreach($fields as $key=>$value){
			$columns[]=$key;
			
			if (array_key_exists($key, $lob_fields)) {
				if ($lob_fields[$key]=='BLOB') {

					$lobs[] = $key;
					$values[] = 'EMPTY_BLOB()';
					$special[$key] =  OCI_B_BLOB;
				}
				elseif ($lob_fields[$key]=='CLOB') {

					$lobs[] = $key;
					$values[] = 'EMPTY_CLOB()';
					$special[$key] =  OCI_B_CLOB;
				}
			}
			else 
				$values[]=$this->set_parameter_colon($key);
			
		}
		$columns=join(", ",$columns);
		$values=join(", ",$values);


		$query=$this->set_lobs("INSERT INTO {$table} ({$columns}) VALUES ({$values})", $lobs);
		
		$sth=$this->execute_query($query, $fields, $special);
	}
	
	
	 
	public function update_record($table, $fields=array(), $special=array(), $where=array()) {	
		if ( !is_array( $fields ) || !count( $fields ) ||
			!is_array( $where ) || !count( $where ) ) return;
	
		if (!is_array($special)) $special=array();
		
		$lob_fields = $this->get_lobs($table);

		$lobs = array();
				
		foreach($fields as $key=>$value){
			if (array_key_exists($key, $lob_fields)) {

				if ($lob_fields[$key]=='BLOB') {

					$pairs[] = "{$key}=EMPTY_BLOB()";
					$special[$key] =  OCI_B_BLOB;
					$lobs[] = $key;
				}
				elseif ($lob_fields[$key]=='CLOB') {

					$pairs[] = "{$key}=EMPTY_CLOB()";
					$special[$key] =  OCI_B_CLOB;
					$lobs[] = $key;
				}					
			}
			else 
				$pairs[]="{$key}=".$this->set_parameter_colon($key);
		}
		
		$pairs=join(", ",$pairs);

		foreach($where as $key=>$value){
			$ands[]="{$key}=:ands_{$key}";
			$fields["ands_".$key]=$value;
		}
		
		$ands=join(" AND ",$ands);

		$query=$this->set_lobs("UPDATE {$table} SET {$pairs} WHERE {$ands}", $lobs);
		
		$sth=$this->execute_query($query, $fields, $special);
		
		return $sth->rowCount();
	}
	

	
	private function set_lobs ($query, $lobs) {
		if (sizeof($lobs)) 
			$query .= ' RETURNING '.implode(', ',$lobs).' INTO '.implode(', ', array_map(array($this, 'set_parameter_colon'), $lobs));
		return $query;
	}
	

	
	
	public function last_insert_id($sequence_name){
		$res = parent::sql_select('SELECT '.$sequence_name.'.CURRVAL AS CURRVAL FROM DUAL');
		return $res[0]['CURRVAL'];
	}
	
	
	
	public function get_primary_key_fields ($obj) {
		return lib::array_reindex($this->sql_select('
								SELECT 
									UCC.COLUMN_NAME 
								FROM 
									USER_CONSTRAINTS UC
										INNER JOIN
											USER_CONS_COLUMNS UCC
												USING (CONSTRAINT_NAME)
								WHERE
									UC.CONSTRAINT_TYPE=\'P\'
										AND
											UC.TABLE_NAME=:obj
		', array('obj'=>$obj)), 'COLUMN_NAME'
		);
	}
	
	
	
	public function get_autoincrement_fields($obj) {
		return lib::array_reindex($this->sql_select('
								SELECT
									COLUMN_NAME
								FROM
									USER_TRIGGER_COLS
								WHERE 
									TRIGGER_NAME = :trigger_name
										AND
											TABLE_NAME = :obj
		', array('trigger_name'=>$obj.'_BI', 'obj'=>$obj)), 'COLUMN_NAME');
	}
}