<?
class Model_Cars extends Model{
	
	public function get_data(){
		$cars = db::sql_select('select * from cars');
		return $cars;
	}
	
	public function get_by_id($id){
		$cars = db::sql_select('select * from cars where id = :id', array('id'=>$id));
		return $cars;
	}
	
	public function add_car($data){
		unset($data['submit']);
		db::sql_query('insert into cars (name, reg_mark, fuel_cons, way_id) 
						values (:name, :reg_mark, :fuel_cons, :way_id)', $data);
	}
	
	public function update_car($id, $data){
		unset($data['submit']);
		$data['id'] = $id;
		db::sql_query('update cars set name = :name, reg_mark = :reg_mark, 
						fuel_cons = :fuel_cons, way_id = :way_id where id = :id', $data);
	}
	
}
