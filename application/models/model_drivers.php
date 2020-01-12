<?
class Model_Drivers extends Model{
	
	public function get_data(){
		$cars = db::sql_select('select * from drivers');
		return $cars;
	}
	
	public function get_by_id($id){
		$cars = db::sql_select('select * from drivers where id = :id', array('id'=>$id));
		return $cars;
	}
	
	public function add_car($data){
		unset($data['submit']);
		$data['password'] = md5($data['password']);
		db::sql_query('insert into drivers (firstname, lastname, car_id, login, password) 
						values (:firstname, :lastname, :car_id, :login, :password)', $data);
	}
	
	public function update_car($id, $data){
		unset($data['submit']);
		$data['id'] = $id;
		$query = 'update drivers set firstname = :firstname, lastname = :lastname, car_id = :car_id, 
						login = :login ';
		if($data['password']){
			$data['password'] = md5($data['password']);
			$query .= ', password = :password ';
		}else{
			unset($data['password']);
		}
		$query .= 'where id = :id';
		db::sql_query($query, $data);
	}
	
}
