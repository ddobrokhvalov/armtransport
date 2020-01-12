<?
class Model_Ways extends Model{
	
	public function get_data(){
		$ways = db::sql_select('select * from ways');
		return $ways;
	}
	
	public function get_by_id($id){
		$ways = db::sql_select('select * from ways where id = :id', array('id'=>$id));
		return $ways;
	}
	
	public function add_way($data){
		unset($data['submit']);
		db::sql_query('insert into ways (name, mileage, norm_cargo_day, norm_cargo_week, norm_cargo_month, norm_fuel_day, norm_fuel_week, norm_fuel_month) 
						values (:name, :mileage, :norm_cargo_day, :norm_cargo_week, :norm_cargo_month, :norm_fuel_day, :norm_fuel_week, :norm_fuel_month)', $data);
	}
	
	public function update_way($id, $data){
		unset($data['submit']);
		$data['id'] = $id;
		db::sql_query('update ways set name = :name, mileage = :mileage, 
						norm_cargo_day = :norm_cargo_day, norm_cargo_week = :norm_cargo_week, norm_cargo_month = :norm_cargo_month, 
						norm_fuel_day = :norm_fuel_day, norm_fuel_week = :norm_fuel_week, norm_fuel_month = :norm_fuel_month where id = :id', $data);
	}
}
