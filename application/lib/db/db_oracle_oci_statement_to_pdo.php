<?
class db_oracle_oci_statement_to_pdo {
	
	private $oci_connection;

	private $oci_parsed_statement;
	
	public $lob_descriptors = array();
	
	public $lob_values = array();
	
	function __construct(&$oci_connection, &$oci_parsed_statement){
		$this->oci_connection =& $oci_connection;
		$this->oci_parsed_statement =& $oci_parsed_statement;
	}
	
	public function __destruct() {
		oci_free_statement($this->oci_parsed_statement);
	}

	public function bindColumn ($column , &$param, $type ) {
		return oci_define_by_name($this->oci_parsed_statement, $column, $param);
	}
	
	public function bindParam ( $parameter , &$value, $data_type=PDO::PARAM_STR ) {
		
		if (in_array($data_type, array(OCI_B_BLOB, OCI_B_CLOB))) {
			
			$this->lob_descriptors[$parameter] = oci_new_descriptor($this->oci_connection, OCI_D_LOB);
			$ret = oci_bind_by_name($this->oci_parsed_statement, $parameter, $this->lob_descriptors[$parameter], -1, $data_type);
			$this->lob_values[$parameter] =& $value;
			return $ret;
		}
		
		return oci_bind_by_name($this->oci_parsed_statement, $parameter, &$value);
	}
	
	public function bindValue ( $parameter , $value,  $data_type=PDO::PARAM_STR) {
		return $this->bindParam($parameter , $value, $data_type );
	}
	
	public function execute ($input_parameters=array(), $autocommit=true) {
		if (sizeof($input_parameters)>0) 
			foreach ($input_parameters as $key=>$value) 
				$this->bindParam($key, $value);

		$r = @oci_execute($this->oci_parsed_statement, OCI_DEFAULT);
		if (!$r) {
			$e = oci_error($this->oci_parsed_statement);
			throw new DBDebugException ($e['message'], "\n".$e['sqltext']."\n ".preg_replace('/Array\s*\((.*)\)/s', '\1', print_r($params, 1)));
		}
		
		$this->save_lobs();

		if ($autocommit)
			oci_commit($this->oci_connection);
	}
	
	private function save_lobs () {
		if (!sizeof($this->lob_values)) return;
		
		foreach ($this->lob_values as $param_name=>$value) {
			$this->lob_descriptors[$param_name]->write($this->lob_values[$param_name]);
			$this->lob_descriptors[$param_name]->free();
		}
	
		$this->lob_descriptors=$this->lob_values=array();
	} 
	
	public function closeCursor() {
		return ocifreecursor($this->oci_parsed_statement);
	}
	
	public function columnCount() {
		return oci_num_fields($this->oci_parsed_statement);
	}
	
	public function errorCode() {
		$err = oci_error();
		if ($err)
			return $err['code'];
		return null;
	}
	
	public function errorInfo () {
		$err = oci_error();
		if ($err)
			return array (
				$err['code'],
				$err['code'],
				$err['message']
			);
		return null;
	}	
	
	public function fetch ($fetch_style=PDO::FETCH_BOTH) {
		switch ($fetch_style) {
			case PDO::FETCH_BOTH : $fetch_style = OCI_BOTH+OCI_RETURN_LOBS; break;
			case PDO::FETCH_ASSOC : $fetch_style = OCI_ASSOC+OCI_RETURN_LOBS; break;
			case PDO::FETCH_NUM : $fetch_style = OCI_NUM; break;
			case PDO::FETCH_BOUND : return oci_fetch($this->oci_parsed_statement);
			case PDO::FETCH_OBJ : return oci_fetch_object($this->oci_parsed_statement);
		}
				
		return oci_fetch_array($this->oci_parsed_statement, $fetch_style);
	}
	
	public function fetchAll ($fetch_style=PDO::FETCH_BOTH, $column_index=0 ) {
		$res = array();

		if ($fetch_style != PDO::FETCH_COLUMN)
			while ($row=$this->fetch($fetch_style)) {$res[]=$row;}
		else
			oci_fetch_all($this->oci_parsed_statement, $res);
		
		return $res;
	}
	
	public function fetchColumn ($column_number=0) {
		$res = $this->fetch(PDO::FETCH_NUM);
		return $res[$column_number];
	}
	
	public function rowCount () {
		return oci_num_rows($this->oci_parsed_statement);
	}
	
}