<?
class Model_Traffic extends Model{
	
	public function get_data(){
		$traffic = db::sql_select('select * from traffic where time_end is NULL');
		return $traffic;
	}
	
	public function get_by_id($id){
		$traffic = db::sql_select('select * from traffic where id = :id and time_end is NULL', array('id'=>$id));
		return $traffic;
	}
	
	public function get_by_car_id($car_id){
		$traffic = db::sql_select('select * from traffic where car_id = :car_id and time_end is NULL', array('car_id'=>$car_id));
		return $traffic;
	}
	
	public function get_free_cars_ways(){
		$cars_ways = db::sql_select('select c1.id as car_id, c1.name as car_name, c1.reg_mark, c1.fuel_cons, w1.id as way_id, w1.name as way_name, w1.mileage from ways w1
									inner join cars c1 on c1.way_id = w1.id
									where not c1.id in (select t1.car_id from traffic t1 where time_end is NULL)');
		
		return $cars_ways;
	}
	
	public function start_traffic($data){
		unset($data['submit']);
		db::sql_query('insert into traffic (way_id, car_id, weight, traffic_type) values (:way_id, :car_id, :weight, :traffic_type)', $data);
		$traffic_id = db::last_insert_id('traffic');
		db::sql_query('insert into traffic_points (traffic_id, mileage, point_name, point_type) values (:traffic_id, :mileage, :point_name, :point_type)', 
						array('traffic_id'=>$traffic_id, 'mileage'=>0, 'point_type'=>'start', 'point_name'=>'Начало рейса'));
	}
	
	public function get_traffic_points($traffic_id){
		$traffic_points = db::sql_select('select * from traffic_points where traffic_id = :traffic_id', array('traffic_id'=>$traffic_id));
		return $traffic_points;
	}
	
	public function traffic_pause($id, $data){
		unset($data['submit']);
		$data['traffic_id'] = $id;
		db::sql_query('insert into traffic_points (traffic_id, mileage, point_name, point_type) values (:traffic_id, :mileage, :point_name, :point_type)', $data);
	}
	
	public function traffic_end($id, $data){
		unset($data['submit']);
		$data['traffic_id'] = $id;
		$data['point_name'] = "Окончание рейса";
		db::sql_query('insert into traffic_points (traffic_id, mileage, point_name, point_type) values (:traffic_id, :mileage, :point_name, :point_type)', $data);
		db::sql_query('update traffic set time_end = NOW() where id = :id', array('id'=>$id));
	}
	public function traffic_continue($id, $data){
		unset($data['submit']);
		$data['traffic_id'] = $id;
		$data['point_name'] = "Продолжение рейса";
		$data['mileage'] = 0;
		db::sql_query('insert into traffic_points (traffic_id, mileage, point_name, point_type) values (:traffic_id, :mileage, :point_name, :point_type)', $data);
	}
}
